<?php

namespace App\Core\Jewelry\Constructor\BlanksAndDiamonds;

use App\Models\Catalog\Diamond;
use App\Models\Jewelry\HL\JewelryBlankDiamonds;
use App\Models\Jewelry\JewelryBlank;
use Illuminate\Support\Collection;
use RuntimeException;

/**
 * Класс, описывающий обновление цен для комплектов оправ и бриллиантов в конструкторе.
 */
class BlanksAndDiamondsPriceUpdater
{
    /**
     * Обновляет цены для всех комплектов оправ и бриллиантов в конструкторе.
     */
    public function execute(): void
    {
        $complects = $this->getComplects();

        $blanks = $this->getBlanks($complects);

        $diamonds = $this->getDiamonds($complects);

        /**
         * @var JewelryBlankDiamonds $item
         */
        foreach ($complects as $complect) {
            $blank = $blanks->get($complect->getBlankId());
            if (!$blank) {
                continue;
            }

            $combination = $this->getCombination($complect, $diamonds);
            if ($combination->count() !== count($complect->getDiamondsIds())) {
                continue;
            }

            $price = (new BlanksAndDiamondsPrice($blank, $combination))->getPrice();
            if ($price === (int)$complect->getPrice()) {
                continue;
            }

            $this->updateComplect($complect, $price);
        }
    }

    /**
     * Возвращает список всех комплектов.
     *
     * @return Collection|JewelryBlankDiamonds[]
     */
    private function getComplects(): Collection
    {
        return JewelryBlankDiamonds::getList();
    }

    /**
     * Получает бриллианты по id из комплектов.
     *
     * @param Collection|JewelryBlankDiamonds[] $complects
     *
     * @return Collection|Diamond[]
     */
    private function getDiamonds(Collection $complects): Collection
    {
        $diamondsIs = $complects->map(static function (JewelryBlankDiamonds $item) {
            return $item->getDiamondsIds();
        })->collapse()->toArray();

        return Diamond::filter(['ID' => $diamondsIs])->getList();
    }

    /**
     * Получает бланки по id из комплектов.
     *
     * @param Collection|JewelryBlankDiamonds[] $complects
     *
     * @return Collection|JewelryBlank[]
     */
    private function getBlanks(Collection $complects): Collection
    {
        $blanksId = $complects->map(static function (JewelryBlankDiamonds $item) {
            return [$item->getBlankId()];
        })->collapse()->unique()->toArray();

        return JewelryBlank::filter(['ID' => $blanksId])->getList();
    }

    /**
     * Сохраняет цену для комплекта.
     *
     * @param JewelryBlankDiamonds $complect
     * @param int                  $price
     */
    private function updateComplect(JewelryBlankDiamonds $complect, int $price): void
    {
        $updateResult = $complect->update(['UF_PRICE' => $price]);

        if (!$updateResult) {
            throw new RuntimeException(
                sprintf('Unable to update price BlanksAndDiamonds %s to value %s', $complect->getId(), $price)
            );
        }
    }

    /**
     * Возвращает коллекцию бриллиантов по id из комплекта.
     *
     * @param JewelryBlankDiamonds $complect
     * @param Collection|Diamond[] $diamonds
     *
     * @return Collection|Diamond[]
     */
    private function getCombination(JewelryBlankDiamonds $complect, Collection $diamonds): Collection
    {
        return new Collection(
            array_map(
                static function (int $diamondId) use ($diamonds) {
                    return $diamonds->get($diamondId);
                },
                $complect->getDiamondsIds()
            )
        );
    }
}

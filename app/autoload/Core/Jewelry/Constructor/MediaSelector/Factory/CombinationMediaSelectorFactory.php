<?php

namespace App\Core\Jewelry\Constructor\MediaSelector\Factory;

use App\Core\Jewelry\Constructor\CombinationRepository;
use App\Core\Jewelry\Constructor\MediaSelector\ReadyProductMediaSelector;
use App\Exceptions\ConstructorCombinationException;
use App\Models\Catalog\Diamond;
use App\Models\Jewelry\HL\JewelryBlankDiamonds;
use App\Models\Jewelry\JewelryBlank;
use App\Models\Jewelry\JewelryBlankSku;
use Illuminate\Support\Collection;

/**
 * Класс для создания объекта ReadyProductMediaSelector с сопутствующей логикой получения данных для объекта
 * по данным сущности "Бриллианты для заготовок"
 *
 * Class CombinationMediaSelectorFactory
 * @package App\Core\Jewelry\Constructor\MediaSelector\Factory
 */
class CombinationMediaSelectorFactory
{
    private CombinationRepository $combinationRepository;

    /**
     * CombinationMediaSelectorFactory constructor.
     */
    public function __construct()
    {
        $this->combinationRepository = new CombinationRepository();
    }

    /**
     * Создаёт объект ReadyProductMediaSelector
     *
     * @param JewelryBlankSku $sku - торговое предложение
     * @param array|null $allMedia - список всех файлов для данного торгового предложения
     * @param JewelryBlank $blank - оправа, выбранная пользователем
     * @param Collection|Diamond[] $diamonds - коллекция камней, которые выбраны пользователем для данной оправы
     * @param int $combinationId - id комбинации или 0
     *
     * @return ReadyProductMediaSelector
     *
     * @throws ConstructorCombinationException
     */
    public function build(
        JewelryBlankSku $sku,
        ?array $allMedia,
        JewelryBlank $blank,
        Collection $diamonds,
        int $combinationId
    ): ReadyProductMediaSelector {

        $diamondsId = $diamonds->pluck('ID')->toArray();

        $combination = $this->combinationRepository->getCombination(
            $blank,
            $diamondsId,
            $combinationId
        );

        $diamond = $this->getFirstDiamondOfCombination($combination, $diamonds);

        $cast = $blank->casts->first();

        return new ReadyProductMediaSelector($sku, $allMedia, $blank, $cast, $diamond);
    }

    /**
     * Возвращает первый бриллиант из комбинации, именно он будет соответствовать первому касту оправы
     *
     * @param JewelryBlankDiamonds $combination
     * @param Collection|Diamond[] $diamonds
     *
     * @return Diamond
     *
     * @throws ConstructorCombinationException
     */
    private function getFirstDiamondOfCombination(JewelryBlankDiamonds $combination, Collection $diamonds): Diamond
    {
        $diamondsIds = $combination->getDiamondsIds();

        $diamondId = (int)reset($diamondsIds);
        if (!$diamondId) {
            throw new ConstructorCombinationException(
                sprintf('Не удалось получить id первого бриллианта из комбинации %s', $combination->getId())
            );
        }

        $diamond = $diamonds->get($diamondId);
        if (!$diamond) {
            throw new ConstructorCombinationException(
                sprintf('Не удалось получитьбриллиант по id %s из комбинации %s', $diamondId, $combination->getId())
            );
        }

        return $diamond;
    }
}

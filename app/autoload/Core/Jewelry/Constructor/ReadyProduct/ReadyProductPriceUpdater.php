<?php

namespace App\Core\Jewelry\Constructor\ReadyProduct;

use App\Core\Jewelry\Constructor\BlanksAndDiamonds\BlanksAndDiamondsPrice;
use App\Models\Jewelry\JewelryConstructorReadyProduct;
use Bitrix\Catalog\Model\Price;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Illuminate\Support\Collection;
use RuntimeException;

/**
 * Класс, описывающий обновление цен готовых изделий конструктора.
 */
class ReadyProductPriceUpdater
{
    /**
     * ReadyProductPriceUpdater constructor.
     *
     * @throws LoaderException
     */
    public function __construct()
    {
        Loader::includeModule('catalog');
    }

    /**
     * Обновляет цены готовых изделий конструктора.
     *
     * @throws ArgumentException
     * @throws ObjectNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function execute(): void
    {
        /**
         * @var JewelryConstructorReadyProduct $readyProduct
         */
        foreach ($this->getReadyProducts() as $readyProduct) {
            // текущая цена
            $readyProductPrice = $this->getReadyProductPrice($readyProduct);
            if ($readyProductPrice === null) {
                continue;
            }

            // новая цена
            $price = (new BlanksAndDiamondsPrice($readyProduct->blankSku->blank, $readyProduct->diamonds))->getPrice();

            if ($price === (int)$readyProductPrice['PRICE']) {
                continue;
            }

            $this->updateReadyProductPrice($readyProduct, $readyProductPrice, $price);
        }
    }

    /**
     * Возвращает список доступных для продажи готовых изделий.
     *
     * @return Collection|JewelryConstructorReadyProduct[]
     */
    private function getReadyProducts(): Collection
    {
        $readyProducts = JewelryConstructorReadyProduct::active()
            ->with([
                'blankSku.blank',
                'diamonds',
            ])
            ->getList();

        // отфильтровываем недоступные для продажи готовые изделия
        // и готовые изделия в которых существуют не все заявленные бриллианты
        $readyProducts = $readyProducts->filter(
            static function (JewelryConstructorReadyProduct $readyProduct) {
                $diamondCountFromProperty = count($readyProduct->getFields()['PROPERTY_DIAMONDS_ID_VALUE']);

                return $diamondCountFromProperty === $readyProduct->diamonds->count()
                    && $readyProduct->isAvailableForSelling();
            }
        );

        return $readyProducts;
    }

    /**
     * Получает текущую цену готового изделия конструктора.
     *
     * @param JewelryConstructorReadyProduct $readyProduct
     *
     * @throws ObjectNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     *
     * @return array|null
     */
    private function getReadyProductPrice(JewelryConstructorReadyProduct $readyProduct): ?array
    {
        $result = Price::getList([
            'filter' => ['=PRODUCT_ID' => $readyProduct->getId()],
            'select' => ['ID', 'PRICE'],
        ])->fetch();

        return is_array($result) ? $result : null;
    }

    /**
     * Обновляет цену готового изделия конструктора.
     *
     * @param JewelryConstructorReadyProduct $readyProduct
     * @param array                          $readyProductPrice
     * @param int                            $price
     */
    private function updateReadyProductPrice($readyProduct, array $readyProductPrice, int $price): void
    {
        $updateResult = Price::update($readyProductPrice['ID'], ['fields' => ['PRICE' => $price]]);

        if (!$updateResult) {
            throw new RuntimeException(
                sprintf(
                    'Unable to update price JewelryConstructorReadyProduct %s to value %s',
                    $readyProduct->getId(),
                    $price
                )
            );
        }
    }
}

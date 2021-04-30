<?php

namespace App\Core\Jewelry\Constructor\MediaSelector\Factory;

use App\Core\Jewelry\Constructor\MediaSelector\ReadyProductMediaSelector;
use App\Helpers\JewelryCastHelper;
use App\Models\Jewelry\JewelryBlankSku;
use App\Models\Jewelry\JewelryConstructorReadyProduct;

/**
 * Класс для создания объекта ReadyProductMediaSelector с сопутствующей логикой получения данных для объекта
 * по данным готового изделия.
 *
 * Class ReadyMediaMediaSelectorFactory
 */
class ReadyMediaMediaSelectorFactory
{
    /**
     * Создаёт объект ReadyProductMediaSelector.
     *
     * @param JewelryBlankSku                $sku          - торговое предложение оправы
     * @param array|null                     $allMedia     - список всех файлов для данного торгового предложения
     * @param JewelryConstructorReadyProduct $readyProduct - готовое изделие
     *
     * @return ReadyProductMediaSelector|null
     */
    public function build(
        JewelryBlankSku $sku,
        ?array $allMedia,
        JewelryConstructorReadyProduct $readyProduct
    ): ?ReadyProductMediaSelector {
        if (empty($allMedia)) {
            return null;
        }

        if (
            $readyProduct->diamonds
            && $readyProduct->diamonds->count() > 1
            && $sku->blank
            && $sku->blank->isTrilogy()
        ) {
            $cast = JewelryCastHelper::getCentralCastOfTrilogy($sku->blank);
            $diamond = JewelryCastHelper::getCentralDiamondOfTrilogy($readyProduct->diamonds, $cast);
        } elseif ($sku->blank && $sku->blank->casts && $readyProduct->diamonds) {
            $cast = $sku->blank->casts->first();
            $diamond = $readyProduct->diamonds->first();
        }

        if (isset($cast, $diamond) && $sku->blank && $cast && $diamond) {
            return new ReadyProductMediaSelector(
                $sku,
                $allMedia,
                $sku->blank,
                $cast,
                $diamond
            );
        }

        return null;
    }
}

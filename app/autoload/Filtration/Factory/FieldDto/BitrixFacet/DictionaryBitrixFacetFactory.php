<?php

namespace App\Filtration\Factory\FieldDto\BitrixFacet;

use App\Filtration\DataProvider\Embed\BitrixFacetFilterData;
use App\Filtration\Model\BitrixFacetIndexItem;

/**
 * Class DictionaryBitrixFacetFactory
 * Генерация DTO по свойству строкового типа
 * (значения хранятся в специальном справочнике)
 *
 * @package App\Filtration\Factory\FieldDto\BitrixFacet
 */
class DictionaryBitrixFacetFactory extends AbstractBitrixFacetFactory
{
    /**
     * @param array $params
     * @return array
     */
    protected function generateVariantItems(array $params): array
    {
        /** @var BitrixFacetFilterData $facetData */
        $facetData = $params['facetData'];

        /** @var array $appliedFilterRequestValues */
        $appliedFilterRequestValues = $params['appliedFilterRequestValues'];

        $dictionaryValues = $this->filterFacetItemsValues(
            // Возвращает реальные значения, которые вернула выборка из индекса для поля
            $facetData->getItemsDictionaryValues()
        );
        natcasesort($dictionaryValues);
        $itemsList = [];
        foreach ($dictionaryValues as $value) {
            $variantItem = [
                '*' => $value,
                'VALUE' => $value,
                'NAME' => $value,
                'DOC_COUNT' => 0,
                'SELECTED' => $this->isValueSelected($value, $appliedFilterRequestValues),
            ];
            /** @var BitrixFacetIndexItem $facetItem */
            if ($value !== '' && $facetItem = $facetData->getFacetItemByDictValue($value)) {
                $variantItem['DOC_COUNT'] = $facetItem->getElementCount();
            }

            $itemsList[] = $variantItem;
        }

        return $itemsList;
    }
}

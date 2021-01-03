<?php

namespace App\Filtration\Factory\FieldDto\BitrixFacet;

use App\Filtration\DataProvider\Embed\BitrixFacetFilterData;
use App\Filtration\Enum\ParamsEnum;
use App\Filtration\Model\BitrixFacetIndexItem;
use App\Filtration\Relation\RelationValuesGetter;
use App\Filtration\Traits\FilterFieldDtoBuilder\Getter\IBlockElementsGetterInnerTrait;

/**
 * Class ElementBitrixFacetFactory
 * Генерация DTO по свойству типа "Привязка к элементу"
 *
 * @package App\Filtration\Factory\FieldDto\BitrixFacet
 */
class ElementBitrixFacetFactory extends AbstractBitrixFacetFactory
{
    use IBlockElementsGetterInnerTrait;

    /** @var int Лимит выборки связанных данных за один запрос */
    protected $queryChunkSize = ParamsEnum::QUERY_CHUNK_SIZE;

    /**
     * @return RelationValuesGetter
     */
    protected function getIBlockElementRelationValuesGetter(): RelationValuesGetter
    {
        return $this->getRelationValueGetter();
    }

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

        $elementsList = $this->getIBlockElementsList(
            $this->filterFacetItemsValues(
                // Возвращает значения (ID элементов), которые вернула выборка из индекса для поля
                $facetData->getItemsValues()
            ),
            (int)($params['iblockId'] ?? 0),
            $this->queryChunkSize
        );

        $itemsList = [];
        foreach ($elementsList as $element) {
            $value = (int)($element['ID'] ?? 0);
            $variantItem = [
                '*' => $element,
                'VALUE' => $value,
                'NAME' => $element['NAME'] ?? '',
                'SORT' => $element['SORT'] ?? 0,
                'DESCRIPTION' => $element['FILTER_DESCRIPTION'] ?? '',
                'DOC_COUNT' => 0,
                'SELECTED' => $this->isValueSelected($value, $appliedFilterRequestValues),
            ];

            /** @var BitrixFacetIndexItem $facetItem */
            if ($value > 0 && $facetItem = $facetData->getFacetItemByValue($value)) {
                $variantItem['DOC_COUNT'] = $facetItem->getElementCount();
            }

            $itemsList[] = $variantItem;
        }

        return $itemsList;
    }
}

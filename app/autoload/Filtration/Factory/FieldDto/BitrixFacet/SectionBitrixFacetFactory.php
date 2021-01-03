<?php

namespace App\Filtration\Factory\FieldDto\BitrixFacet;

use App\Filtration\DataProvider\Embed\BitrixFacetFilterData;
use App\Filtration\Enum\ParamsEnum;
use App\Filtration\Model\BitrixFacetIndexItem;
use App\Filtration\Relation\RelationValuesGetter;
use App\Filtration\Traits\FilterFieldDtoBuilder\Getter\IBlockSectionsGetterInnerTrait;

/**
 * Class SectionBitrixFacetFactory
 * Генерация DTO по свойству типа "Привязка к разделам"
 *
 * @package App\Filtration\Factory\FieldDto\BitrixFacet
 */
class SectionBitrixFacetFactory extends AbstractBitrixFacetFactory
{
    use IBlockSectionsGetterInnerTrait;

    /** @var int Лимит выборки связанных данных за один запрос */
    protected $queryChunkSize = ParamsEnum::QUERY_CHUNK_SIZE;

    /**
     * @return RelationValuesGetter
     */
    protected function getIBlockSectionRelationValuesGetter(): RelationValuesGetter
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

        $sectionsList = $this->getIBlockSectionsList(
            $this->filterFacetItemsValues(
                // Возвращает значения (ID секций), которые вернула выборка из индекса для поля
                $facetData->getItemsValues()
            ),
            (int)($params['iblockId'] ?? 0),
            $this->queryChunkSize
        );

        $itemsList = [];
        foreach ($sectionsList as $section) {
            $value = (int)($section['ID'] ?? 0);
            $variantItem = [
                '*' => $section,
                'VALUE' => $value,
                'NAME' => $section['NAME'] ?? '',
                'SORT' => $section['LEFT_MARGIN'] ?? 0,
                'DESCRIPTION' => $section['FILTER_DESCRIPTION'] ?? '',
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

<?php

namespace App\Filtration\Factory\FieldDto\BitrixFacet;

use App\Filtration\DataProvider\Embed\BitrixFacetFilterData;
use App\Filtration\Dto\FieldVariantDto;
use App\Filtration\Enum\DisplayTypeEnum;

/**
 * Class RangeBitrixFacetFactory
 *
 * @package App\Filtration\Factory\FieldDto\BitrixFacet
 */
class RangeBitrixFacetFactory extends AbstractBitrixFacetFactory
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

        $minMaxValues = $this->filterFacetItemsValues(
            // Возвращает min-max-значения, которые вернула выборка из индекса для поля
            $facetData->getItemsRangeValues()
        );

        $itemsList = [];
        if (!$minMaxValues) {
            return $itemsList;
        }

        /**
         * @todo Надо бы отвязаться здесь от FieldVariantDto и привязаться к тому объекту, который фактически заполняется
         * (это может быть наследник FieldVariantDto с измененными константами)
         */
        /** @var string|FieldVariantDto $variantDtoEntity */
        $variantDtoEntity = FieldVariantDto::class;

        foreach ($minMaxValues as $item) {
            $value = (float)($item['min'] ?? 0);
            $valueType = (string)($item['valueType'] ?? '');
            $rangeCode = $valueType === DisplayTypeEnum::RANGE_VALUE_TYPE_FILTERED
                ? $variantDtoEntity::RANGE_CODE_MIN_FILTERED
                : $variantDtoEntity::RANGE_CODE_MIN;
            $facetItem = $facetData->getFacetItemByNum($value, $valueType);
            $itemsList[] = [
                '*' => $item,
                'VALUE' => $value,
                'NAME' => (string)$value,
                'RANGE_CODE' => $rangeCode,
                'DOC_COUNT' => $facetItem ? $facetItem->getElementCount() : 0,
                'SELECTED' => $this->isValueSelected($value, $appliedFilterRequestValues),
                'EXTRA' => [
                    'valueType' => $valueType,
                ],
            ];

            $value = (float)($item['max'] ?? 0);
            $valueType = (string)($item['valueType'] ?? '');
            $rangeCode = $valueType === DisplayTypeEnum::RANGE_VALUE_TYPE_FILTERED
                ? $variantDtoEntity::RANGE_CODE_MAX_FILTERED
                : $variantDtoEntity::RANGE_CODE_MAX;
            $facetItem = $facetData->getFacetItemByNum($value, $valueType);
            $itemsList[] = [
                '*' => $item,
                'VALUE' => $value,
                'NAME' => (string)$value,
                'RANGE_CODE' => $rangeCode,
                'DOC_COUNT' => $facetItem ? $facetItem->getElementCount() : 0,
                'SELECTED' => $this->isValueSelected($value, $appliedFilterRequestValues),
                'EXTRA' => [
                    'valueType' => $valueType,
                ],
            ];
        }

        foreach (($params['appliedFilterRequestValues'] ?? []) as $rangeCode => $value) {
            if ($rangeCode === $variantDtoEntity::RANGE_CODE_FROM || $rangeCode === $variantDtoEntity::RANGE_CODE_TO) {
                $value = (float)$value;
                $facetItem = $facetData->getFacetItemByNum($value);
                $itemsList[] = [
                    '*' => $value,
                    'VALUE' => $value,
                    'NAME' => $value,
                    'RANGE_CODE' => $rangeCode,
                    'DOC_COUNT' => $facetItem ? $facetItem->getElementCount() : 0,
                    'SELECTED' => $this->isValueSelected($value, $appliedFilterRequestValues),
                ];
            }
        }

        return $itemsList;
    }
}

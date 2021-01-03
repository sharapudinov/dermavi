<?php
/** @noinspection PhpUnusedParameterInspection */

namespace App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet;

use App\Filtration\Enum\RequestEnum;
use Bitrix\Iblock\PropertyIndex\Facet;

/**
 * Trait BitrixFacetFiltersInnerGeneratorNumTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet
 */
trait BitrixFacetFiltersInnerGeneratorNumTrait
{
    /**
     * @param array $appliedValues
     * @param Facet $facet
     * @param int $propertyId ID свойства
     * @param mixed|null $filterValues
     * @return array
     */
    protected function generateBitrixFacetFiltersInner(
        array &$appliedValues,
        Facet $facet,
        int $propertyId,
        $filterValues = null
    ): array
    {
        $facetFilters = [];
        if ($filterValues && is_array($filterValues) && $propertyId > 0) {
            $from = $filterValues[RequestEnum::RANGE_FIELD_FROM] ?? null;
            $to = $filterValues[RequestEnum::RANGE_FIELD_TO] ?? null;
            if ($from !== null) {
                $facetFilters[] = [
                    'entity' => 'numeric',
                    'entityId' => $propertyId,
                    'operator' => '>=',
                    'value' => $from,
                ];
                $appliedValues[RequestEnum::RANGE_FIELD_FROM] = $from;
            }
            if ($to !== null) {
                $facetFilters[] = [
                    'entity' => 'numeric',
                    'entityId' => $propertyId,
                    'operator' => '<=',
                    'value' => $to,
                ];
                $appliedValues[RequestEnum::RANGE_FIELD_TO] = $to;
            }
        }

        return $facetFilters;
    }
}

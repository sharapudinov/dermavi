<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet;

use Bitrix\Iblock\PropertyIndex\Facet;

/**
 * Trait BitrixFacetFiltersInnerGeneratorStrTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet
 */
trait BitrixFacetFiltersInnerGeneratorStrTrait
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
        if ($filterValues !== null && $propertyId > 0) {
            $filterValues = is_array($filterValues) ? $filterValues : [$filterValues];
            foreach ($filterValues as $value) {
                $value = trim((string)$value);
                if ($value === '') {
                    continue;
                }
                if ($dictValueId = $facet->getDictionary()->getStringId($value, false)) {
                    $facetFilters[] = [
                        'entity' => 'dictionary',
                        'entityId' => $propertyId,
                        'operator' => '=',
                        'value' => $dictValueId,
                    ];
                    $appliedValues[] = $value;
                }
            }
        }

        return $facetFilters;
    }
}

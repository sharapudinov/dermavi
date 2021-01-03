<?php
/** @noinspection PhpUnusedParameterInspection */

namespace App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet;

use Bitrix\Iblock\PropertyIndex\Facet;

/**
 * Trait BitrixFacetFiltersInnerGeneratorIntTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet
 */
trait BitrixFacetFiltersInnerGeneratorIntTrait
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
                $value = (int)$value;
                if ($value <= 0) {
                    continue;
                }
                $facetFilters[] = [
                    'entity' => 'dictionary',
                    'entityId' => $propertyId,
                    'operator' => '=',
                    'value' => $value,
                ];
                $appliedValues[] = $value;
            }
        }

        return $facetFilters;
    }
}

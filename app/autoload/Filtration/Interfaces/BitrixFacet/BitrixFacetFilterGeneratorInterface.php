<?php

namespace App\Filtration\Interfaces\BitrixFacet;

use App\Filtration\Interfaces\FilterRequestInterface;
use Bitrix\Iblock\PropertyIndex\Facet;

/**
 * Interface BitrixFacetFilterGeneratorInterface
 *
 * @package App\Filtration\Interfaces\BitrixFacet
 */
interface BitrixFacetFilterGeneratorInterface
{
    /**
     * @param array $appliedValues
     * @param Facet $facet
     * @param int $facetEntityId
     * @param FilterRequestInterface $filterRequest
     * @return array
     */
    public function generateBitrixFacetFilters(
        array &$appliedValues,
        Facet $facet,
        int $facetEntityId,
        FilterRequestInterface $filterRequest
    ): array;
}

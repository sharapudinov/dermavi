<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet;

use Bitrix\Iblock\PropertyIndex\Facet;

/**
 * Trait FacetFilterRequestApplyingTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet
 */
trait FacetFilterRequestApplyingTrait
{
    /**
     * @param Facet $facet
     * @param array $facetFilters
     * @return static
     */
    protected function applyFacetFilters(Facet $facet, array $facetFilters)
    {
        foreach ($facetFilters as $filterInfo) {
            switch ($filterInfo['entity']) {
                case 'dictionary':
                    $facet->addDictionaryPropertyFilter(
                        $filterInfo['entityId'],
                        $filterInfo['operator'],
                        $filterInfo['value']
                    );
                    break;

                case 'date':
                    $facet->addDatetimePropertyFilter(
                        $filterInfo['entityId'],
                        $filterInfo['operator'],
                        $filterInfo['value']
                    );
                    break;

                case 'numeric':
                    $facet->addNumericPropertyFilter(
                        $filterInfo['entityId'],
                        $filterInfo['operator'],
                        $filterInfo['value']
                    );
                    break;

                case 'price':
                    $facet->addPriceFilter(
                        $filterInfo['entityId'],
                        $filterInfo['operator'],
                        $filterInfo['value']
                    );
                    break;
            }
        }

        return $this;
    }
}

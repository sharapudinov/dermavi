<?php

namespace App\Filtration\FilterFieldDtoBuilder\BitrixFacet;

use App\Filtration\Factory\FieldDto\AbstractFieldDtoFactory;
use App\Filtration\Factory\FieldDto\BitrixFacet\RangeBitrixFacetFactory;
use App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet\BitrixFacetFiltersInnerGeneratorNumTrait;

/**
 * Class AbstractRangeBitrixFacet
 * Числовые свойства
 *
 * @package App\Filtration\FilterFieldDtoBuilder\BitrixFacet
 */
abstract class AbstractRangeBitrixFacet extends AbstractBitrixFacet
{
    use BitrixFacetFiltersInnerGeneratorNumTrait;

    /**
     * @return RangeBitrixFacetFactory
     */
    protected function buildFieldDtoFactory(): AbstractFieldDtoFactory
    {
        return new RangeBitrixFacetFactory();
    }
}

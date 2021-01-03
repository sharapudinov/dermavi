<?php

namespace App\Filtration\FilterFieldDtoBuilder\BitrixFacet;

use App\Filtration\Factory\FieldDto\AbstractFieldDtoFactory;
use App\Filtration\Factory\FieldDto\BitrixFacet\SectionBitrixFacetFactory;
use App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet\BitrixFacetFiltersInnerGeneratorIntTrait;

/**
 * Class AbstractSectionBitrixFacet
 * Свойства привязки к секциям
 *
 * @package App\Filtration\FilterFieldDtoBuilder\BitrixFacet
 */
abstract class AbstractSectionBitrixFacet extends AbstractBitrixFacet
{
    use BitrixFacetFiltersInnerGeneratorIntTrait;

    /**
     * @return SectionBitrixFacetFactory
     */
    protected function buildFieldDtoFactory(): AbstractFieldDtoFactory
    {
        return new SectionBitrixFacetFactory();
    }
}

<?php

namespace App\Filtration\FilterFieldDtoBuilder\BitrixFacet;

use App\Filtration\Factory\FieldDto\AbstractFieldDtoFactory;
use App\Filtration\Factory\FieldDto\BitrixFacet\ElementBitrixFacetFactory;
use App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet\BitrixFacetFiltersInnerGeneratorIntTrait;

/**
 * Class AbstractElementBitrixFacet
 * Свойства привязки к элементам
 *
 * @package App\Filtration\FilterFieldDtoBuilder\BitrixFacet
 */
abstract class AbstractElementBitrixFacet extends AbstractBitrixFacet
{
    use BitrixFacetFiltersInnerGeneratorIntTrait;

    /**
     * @return ElementBitrixFacetFactory
     */
    protected function buildFieldDtoFactory(): AbstractFieldDtoFactory
    {
        return new ElementBitrixFacetFactory();
    }
}

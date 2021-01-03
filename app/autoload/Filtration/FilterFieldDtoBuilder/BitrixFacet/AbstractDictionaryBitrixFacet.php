<?php

namespace App\Filtration\FilterFieldDtoBuilder\BitrixFacet;

use App\Filtration\Factory\FieldDto\AbstractFieldDtoFactory;
use App\Filtration\Factory\FieldDto\BitrixFacet\DictionaryBitrixFacetFactory;
use App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet\BitrixFacetFiltersInnerGeneratorStrTrait;

/**
 * Class AbstractDictionaryBitrixFacet
 * Свойства строкового типа
 *
 * @package App\Filtration\FilterFieldDtoBuilder\BitrixFacet
 */
abstract class AbstractDictionaryBitrixFacet extends AbstractBitrixFacet
{
    use BitrixFacetFiltersInnerGeneratorStrTrait;

    /**
     * @return DictionaryBitrixFacetFactory
     */
    protected function buildFieldDtoFactory(): AbstractFieldDtoFactory
    {
        return new DictionaryBitrixFacetFactory();
    }
}

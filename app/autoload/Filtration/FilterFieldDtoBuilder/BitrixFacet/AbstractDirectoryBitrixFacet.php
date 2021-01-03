<?php

namespace App\Filtration\FilterFieldDtoBuilder\BitrixFacet;

use App\Filtration\Factory\FieldDto\AbstractFieldDtoFactory;
use App\Filtration\Factory\FieldDto\BitrixFacet\DirectoryBitrixFacetFactory;
use App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet\BitrixFacetFiltersInnerGeneratorStrTrait;

/**
 * Class AbstractDirectoryBitrixFacet
 * Свойства "Справочник"
 *
 * @package App\Filtration\FilterFieldDtoBuilder\BitrixFacet
 */
abstract class AbstractDirectoryBitrixFacet extends AbstractBitrixFacet
{
    use BitrixFacetFiltersInnerGeneratorStrTrait;

    /**
     * @return DirectoryBitrixFacetFactory
     */
    protected function buildFieldDtoFactory(): AbstractFieldDtoFactory
    {
        return new DirectoryBitrixFacetFactory();
    }
}

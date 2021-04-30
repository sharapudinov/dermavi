<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\CuletFilter;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class CuletFilterField
 *
 * @package App\Core\Product\FilterField\Facet
 */
class CuletFilterField extends AbstractDirectoryFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = CuletFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'culets';
}

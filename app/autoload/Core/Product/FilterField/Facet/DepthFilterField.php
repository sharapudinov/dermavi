<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\DepthFilter;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class DepthFilterField
 *
 * @package App\Core\Product\FilterField\Facet
 */
class DepthFilterField extends AbstractRangeFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = DepthFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'depth';
}

<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\TableFilter;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class TableFilterField
 *
 * @package App\Core\Product\FilterField\Facet
 */
class TableFilterField extends AbstractRangeFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = TableFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'table';
}

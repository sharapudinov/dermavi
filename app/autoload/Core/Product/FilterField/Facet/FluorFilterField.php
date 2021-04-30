<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\FluorFilter;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class FluorFilterField
 *
 * @package App\Core\Product\FilterField\Facet
 */
class FluorFilterField extends AbstractDirectoryFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = FluorFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'fluorescences';

    /** @var array */
    protected $directoryItemsSort = [
        'UF_SORT' => 'ASC',
    ];
}

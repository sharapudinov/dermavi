<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\SymmetryFilter;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class SymmetryFilterField
 *
 * @package App\Core\Product\FilterField\Facet
 */
class SymmetryFilterField extends AbstractDirectoryFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = SymmetryFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'symmetries';

    /** @var array Исключаемые значения справочника по UF_NAME (в нижнем регистре) */
    protected $directoryItemsExcludedNames = [
        'none', 'poor', 'fair',
    ];
}

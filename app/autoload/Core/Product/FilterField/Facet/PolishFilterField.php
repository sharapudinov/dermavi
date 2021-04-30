<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\PolishFilter;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class PolishFilterField
 *
 * @package App\Core\Product\FilterField\Facet
 */
class PolishFilterField extends AbstractDirectoryFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = PolishFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'polishes';

    /** @var array Исключаемые значения справочника по UF_NAME (в нижнем регистре) */
    protected $directoryItemsExcludedNames = [
        'poor', 'fair', 'none',
    ];
}

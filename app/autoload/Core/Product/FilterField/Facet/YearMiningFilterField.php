<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\YearMiningFilter;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class YearMiningFilterField
 *
 * @package App\Core\Product\FilterField\Facet
 */
class YearMiningFilterField extends AbstractDictionaryFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = YearMiningFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'years_of_mining';
}

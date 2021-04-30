<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\AgeFilter;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class AgeFilterField
 *
 * @todo Не используется. Нужно изменить тип свойства на "S" и включить в фасетный индекс
 *
 * @package App\Core\Product\FilterField\Facet
 */
class AgeFilterField extends AbstractRangeFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = AgeFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'ages';
}

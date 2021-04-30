<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\IntensityIdFilter;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class IntensityIdFilterField
 *
 * @todo Не используется. Нужно включить в фасетный индекс
 *
 * @package App\Core\Product\FilterField\Facet
 */
class IntensityIdFilterField extends AbstractDictionaryFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = IntensityIdFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'intensity_id';
}

<?php

namespace App\Core\Product\FilterField;

use App\Core\User\Traits\UserContextAwareTrait;
use App\Core\User\Interfaces\UserContextAwareInterface;
use App\Filtration\FilterField\BaseRegularRangeFilterField;

/**
 * Class AbstractRangeFilter
 * Фильтр диапазонного поля для использования в фильтрах в виде массива
 *
 * @package App\Core\Diamond\FilterField
 */
abstract class AbstractRangeFilter extends BaseRegularRangeFilterField implements UserContextAwareInterface
{
    public const PROPERTY_CODE = '';

    use UserContextAwareTrait;
}

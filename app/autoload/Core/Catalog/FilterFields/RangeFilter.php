<?php

namespace App\Core\Catalog\FilterFields;

/**
 * Для фильтров с диапазоном значений
 *
 * Class RangeFilter
 * @package App\Core\Catalog\FilterFields
 */
class RangeFilter implements TransformFilterInterface
{
    const PATH_RULE = '/([0-9.]+)_to_([0-9.]+)/';

    public static function getTransformFilterFromUrl($filter): array
    {
        preg_match(self::PATH_RULE, $filter, $matches);

        return [
            'from' => (float)$matches[1],
            'to'   => (float)$matches[2],
        ];
    }
}

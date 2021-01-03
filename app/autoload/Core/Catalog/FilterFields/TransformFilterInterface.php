<?php

namespace App\Core\Catalog\FilterFields;

/**
 * Interface TransformFilterInterface
 * @package App\Core\Catalog\FilterFields
 */
interface TransformFilterInterface
{
    /**
     * Формирует из url фильтр для сущности
     *
     * @param array|string $filter
     *
     * @return array
     */
    public static function getTransformFilterFromUrl($filter): array;
}

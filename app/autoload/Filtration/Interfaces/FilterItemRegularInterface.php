<?php

namespace App\Filtration\Interfaces;

/**
 * Interface FilterItemRegularInterface
 * Элемент фильтра в виде массива
 *
 * @package App\Filtration\Interfaces
 */
interface FilterItemRegularInterface extends FilterItemInterface
{
    /**
     * Возвращает фильтр по полю
     *
     * @param null $params
     * @return array|null
     */
    public function getValue($params = null): ?array;
}

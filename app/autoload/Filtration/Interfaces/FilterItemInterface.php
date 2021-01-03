<?php

namespace App\Filtration\Interfaces;

use Throwable;

/**
 * Interface FilterItemInterface
 * Элемент фильтра
 *
 * @package App\Filtration\Interfaces
 */
interface FilterItemInterface
{
    /**
     * Возвращает фильтр по полю
     *
     * @param mixed|null $params
     * @return mixed|null
     * @throws Throwable
     */
    public function getValue($params = null);

    /**
     * @return FilterFieldInterface|null
     */
    public function getFilterField(): ?FilterFieldInterface;
}

<?php

namespace App\Filtration\Interfaces;

/**
 * Interface FilterRequestValueAwareInterface
 * Интерфейс значений запросов для фильтрации
 *
 * @package App\Filtration\Interfaces
 */
interface FilterRequestValueAwareInterface
{
    /**
     * Возвращает значение для фильтрации по полю
     *
     * @param string $filterName
     * @return mixed|null
     */
    public function getValue(string $filterName);

    /**
     * @return array
     */
    public function getAllValues(): array;
}

<?php

namespace App\Filtration\Interfaces;

/**
 * Interface FilterFieldInterface
 * Фильтрация поля
 *
 * @package App\Filtration\Interfaces
 */
interface FilterFieldInterface
{
    /**
     * Возвращает имя фильтруемого поля в запросе
     *
     * @return string
     */
    public function getRequestFieldName(): string;

    /**
     * Возвращает имя фильтруемого поля
     *
     * @return string
     */
    public function getFilterFieldName(): string;

    /**
     * Возвращает фильтр по полю
     *
     * @param FilterRequestInterface $filterRequest
     * @return FilterItemInterface|null
     */
    public function getFilterItem(FilterRequestInterface $filterRequest): ?FilterItemInterface;

    /**
     * Нужно ли пропускать поле при генерации фильтра
     *
     * @return bool
     */
    public function isExcludedInFilter(): bool;
}

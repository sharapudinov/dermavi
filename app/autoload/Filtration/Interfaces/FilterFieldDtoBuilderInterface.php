<?php

namespace App\Filtration\Interfaces;

use App\Filtration\Dto\FieldDto;
use Throwable;

/**
 * Interface FilterFieldDtoBuilderInterface
 * Генератор DTO поля фильтра
 *
 * @package App\Filtration\Interfaces
 */
interface FilterFieldDtoBuilderInterface
{
    /**
     * Генерирует DTO поля фильтра
     *
     * @param $data
     * @return FieldDto
     * @throws Throwable
     */
    public function buildFieldDto($data): FieldDto;

    /**
     * @return FilterFieldInterface
     */
    public function getFilterField(): FilterFieldInterface;

    /**
     * Возвращает первичный ключ поля.
     * Для свойства инфоблока - ID свойства, для цены - ID цены, для внутреннего поля - его имя
     *
     * @return string|int
     */
    public function getFilterFieldEntityPrimaryKey();

    /**
     * Возвращает код для идентификации поля
     *
     * @return string
     */
    public function getFilterFieldDtoCode(): string;
}

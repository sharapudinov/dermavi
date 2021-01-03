<?php

namespace App\Filtration\FilterRequest;

use App\Filtration\Interfaces\FilterRequestInterface;

/**
 * Class ArrayRequest
 * Пустой запрос для фильтрации
 *
 * @package App\Filtration\FilterRequest
 */
class NullRequest implements FilterRequestInterface
{
    /**
     * Возвращает значение для фильтрации по полю
     *
     * @param string $filterName
     * @return mixed|null
     */
    public function getValue(string $filterName)
    {
        return null;
    }

    /**
     * @return array
     */
    public function getAllValues(): array
    {
        return [];
    }
}

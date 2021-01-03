<?php

namespace App\Filtration\FilterRequest;

use App\Filtration\Interfaces\FilterRequestInterface;

/**
 * Class ArrayRequest
 * Запрос для фильтрации на основе массива
 *
 * @package App\Filtration\FilterRequest
 */
class ArrayRequest implements FilterRequestInterface
{
    /** @var array */
    protected $request;

    /**
     * @param array $request
     */
    public function __construct(array $request = [])
    {
        $this->request = $request;
    }

    /**
     * Возвращает значение для фильтрации по полю
     *
     * @param string $filterName
     * @return mixed|null
     */
    public function getValue(string $filterName)
    {
        return $this->request[$filterName] ?? null;
    }

    /**
     * @param string $filterName
     * @param mixed $value
     * @return static
     */
    public function setValue(string $filterName, $value)
    {
        $this->request[$filterName] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getAllValues(): array
    {
        return $this->request;
    }
}

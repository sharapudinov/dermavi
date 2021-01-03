<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder;

use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Trait WithFilterFieldTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder
 */
trait WithFilterFieldTrait
{
    /** @var FilterFieldInterface */
    protected $filterField;

    /**
     * @return FilterFieldInterface
     */
    abstract protected function buildFilterField(): FilterFieldInterface;

    /**
     * Возвращает фильтрацию поля
     *
     * @return FilterFieldInterface
     */
    public function getFilterField(): FilterFieldInterface
    {
        if ($this->filterField === null) {
            $this->filterField = $this->buildFilterField();
        }

        return $this->filterField;
    }

    /**
     * @param FilterFieldInterface $filterField
     * @return static
     */
    public function setFilterField(FilterFieldInterface $filterField)
    {
        $this->filterField = $filterField;

        return $this;
    }
}

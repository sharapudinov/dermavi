<?php

namespace App\Filtration\FilterItem;

use App\Filtration\Interfaces\FilterFieldInterface;
use App\Filtration\Interfaces\FilterItemInterface;

/**
 * Class AbstractFilterItem
 * Абстрактный класс элемента фильтра
 *
 * @package App\Filtration\FilterItem
 */
abstract class AbstractFilterItem implements FilterItemInterface
{
    /** @var FilterFieldInterface|null */
    protected $filterField;

    /**
     * @param FilterFieldInterface|null $filterField
     */
    public function __construct(FilterFieldInterface $filterField = null)
    {
        if ($filterField) {
            $this->setFilterField($filterField);
        }
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

    /**
     * @return FilterFieldInterface|null
     */
    public function getFilterField(): ?FilterFieldInterface
    {
        return $this->filterField;
    }

    /**
     * Возвращает фильтр по полю
     *
     * @param mixed|null $params
     * @return mixed
     */
    abstract public function getValue($params = null);
}

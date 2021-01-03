<?php

namespace App\Filtration\Factory;

use App\Filtration\Collection\FilterItemCollection;
use App\Filtration\Exception\FilterItemInstanceException;
use App\Filtration\Interfaces\FilterItemInterface;
use Throwable;

/**
 * Class AbstractFilterFactory
 *
 * @package App\Filtration\Factory
 */
abstract class AbstractFilterFactory
{
    /**
     * Логика генератора фильтра
     *
     * @internal
     * @param FilterItemCollection $filterItemCollection
     * @throws Throwable
     * @return mixed
     */
    abstract protected function internalCreate(FilterItemCollection $filterItemCollection);

    /**
     * @return string
     */
    abstract protected function getSupportedInstance(): string;

    /**
     * Генерация фильтра в виде массива, объекта или строки
     *
     * @param FilterItemCollection $filterItemCollection
     * @return mixed
     * @throws Throwable
     * @throws FilterItemInstanceException
     */
    final public function create(FilterItemCollection $filterItemCollection)
    {
        $this->validateFilterItemCollection($filterItemCollection);

        return $this->internalCreate($filterItemCollection);
    }

    /**
     * @param FilterItemCollection $filterItemCollection
     * @throws FilterItemInstanceException
     */
    protected function validateFilterItemCollection(FilterItemCollection $filterItemCollection): void
    {
        foreach ($filterItemCollection as $item) {
            $this->validateFilterItem($item);
        }
    }

    /**
     * @param FilterItemInterface $filterItem
     * @throws FilterItemInstanceException
     */
    protected function validateFilterItem(FilterItemInterface $filterItem): void
    {
        $className = static::getSupportedInstance();
        if (!($filterItem instanceof $className)) {
            throw new FilterItemInstanceException(
                'Filter item class must be an instance of ' . $className
            );
        }
    }
}

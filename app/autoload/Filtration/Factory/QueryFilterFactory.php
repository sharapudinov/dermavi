<?php

namespace App\Filtration\Factory;

use App\Filtration\Collection\FilterItemCollection;
use App\Filtration\Interfaces\FilterItemQueryInterface;
use Bitrix\Main\ORM\Query\Query;
use Throwable;

/**
 * Class QueryFilterFactory
 * Фабрика генерации фильтра в виде объекта Query
 *
 * @package App\Filtration\Factory
 *
 * @method Query create
 */
class QueryFilterFactory extends AbstractFilterFactory
{
    /** @var Query */
    protected $query;

    /**
     * @param Query $query
     */
    public function __construct(Query $query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    protected function getSupportedInstance(): string
    {
        return FilterItemQueryInterface::class;
    }

    /**
     * Генерация фильтра
     *
     * @param FilterItemCollection|FilterItemQueryInterface[] $filterItemCollection
     * @return Query
     * @throws Throwable
     */
    protected function internalCreate(FilterItemCollection $filterItemCollection): Query
    {
        $query = clone $this->query;
        foreach ($filterItemCollection as $item) {
            $query = $item->getValue($query);
        }

        return $query;
    }
}

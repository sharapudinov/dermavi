<?php

namespace App\Filtration\FilterItem;

use App\Filtration\Exception\FilterItemArgumentException;
use App\Filtration\Interfaces\FilterFieldInterface;
use App\Filtration\Interfaces\FilterItemQueryInterface;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\SystemException;
use Throwable;

/**
 * Class QueryFilterItem
 * Элемент фильтра для объекта Query
 *
 * @package App\Filtration\FilterItem
 */
class QueryFilterItem extends AbstractFilterItem implements FilterItemQueryInterface
{
    /** @var array|callable|null */
    protected $queryTransformer;

    /**
     * @param array|callable|null $queryTransformer
     * @param FilterFieldInterface|null $filterField
     */
    public function __construct($queryTransformer = null, FilterFieldInterface $filterField = null)
    {
        parent::__construct($filterField);
        if (is_array($queryTransformer) || is_callable($queryTransformer)) {
            $this->setQueryTransformer($queryTransformer);
        }
    }

    /**
     * Возвращает фильтр по полю
     *
     * @param Query|null $query
     * @return mixed
     * @throws FilterItemArgumentException
     * @throws ArgumentException
     * @throws SystemException
     * @throws Throwable
     */
    public function getValue($query = null): Query
    {
        if (!($query instanceof Query)) {
            throw new FilterItemArgumentException(
                'Query argument must be an instance of ' . Query::class
            );
        }

        $this->obtainQuery($query);

        return $query;
    }

    /**
     * @param Query $query
     * @throws ArgumentException
     * @throws SystemException
     * @throws Throwable
     */
    protected function obtainQuery(Query $query): void
    {
        if ($this->queryTransformer === null) {
            return;
        }

        if (is_callable($this->queryTransformer)) {
            $this->execCallableTransformer($this->queryTransformer, $query);

            return;
        }

        if (is_array($this->queryTransformer)) {
            $this->execArrayTransformer($this->queryTransformer, $query);

            return;
        }
    }

    /**
     * @param callable $transformer
     * @param Query $query
     * @throws Throwable
     */
    protected function execCallableTransformer(callable $transformer, Query $query): void
    {
        $transformer($query);
    }

    /**
     * @param array $transformer
     * @param Query $query
     * @throws ArgumentException
     * @throws SystemException
     */
    protected function execArrayTransformer(array $transformer, Query $query): void
    {
        if ($filter = ($transformer['filter'] ?? [])) {
            $query->setFilter(array_merge($query->getFilter(), $filter));
        }
        if ($runtimeFields = ($transformer['registerRuntimeField'] ?? [])) {
            foreach ($runtimeFields as $runtimeFieldParams) {
                $query->registerRuntimeField(
                    $runtimeFieldParams['name'],
                    $runtimeFieldParams['fieldInfo'] ?? null
                );
            }
        }
    }

    /**
     * @param array|callable $queryTransformer
     * @return static
     */
    public function setQueryTransformer($queryTransformer)
    {
        $this->queryTransformer = $queryTransformer;

        return $this;
    }
}

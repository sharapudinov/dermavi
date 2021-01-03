<?php

namespace App\Filtration\FilterField;

use App\Filtration\FilterItem\QueryFilterItem;
use App\Filtration\Interfaces\FilterItemInterface;
use App\Filtration\Traits\FilterField as FilterFieldTraits;

/**
 * Class BaseQueryPlainFilterField
 * Описание правил фильтрации простого поля для объекта Query
 *
 * @package App\Filtration\FilterField
 */
class BaseQueryPlainFilterField extends AbstractFilterField
{
    use FilterFieldTraits\WithRequestFieldNameTrait,
        FilterFieldTraits\WithFilterFieldNameTrait,
        FilterFieldTraits\WithFilterRuleTrait;

    /**
     * @param array|string $requestValue
     * @return QueryFilterItem
     */
    protected function generateFilterItemResult($requestValue): QueryFilterItem
    {
        $filterField = $this->getFilterRule() . $this->getFilterFieldName();
        $result = new QueryFilterItem(
            [
                $filterField => is_array($requestValue) ? array_unique($requestValue) : $requestValue,
            ],
            $this
        );

        return $result;
    }

    /**
     * @param mixed $requestValue
     * @return QueryFilterItem|null
     */
    protected function generateFilterItem($requestValue): ?FilterItemInterface
    {
        $result = null;
        if ($requestValue !== null) {
            $result = $this->generateFilterItemResult($requestValue);
        }

        return $result;
    }
}

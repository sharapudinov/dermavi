<?php

namespace App\Filtration\FilterField;

use App\Filtration\FilterItem\RegularFilterItem;
use App\Filtration\Interfaces\FilterItemInterface;
use App\Filtration\Traits\FilterField as FilterFieldTraits;

/**
 * Class BaseRegularPlainFilterField
 * Описание правил фильтрации простого поля для использования в фильтрах в виде массива
 *
 * @package App\Filtration\FilterField
 */
class BaseRegularPlainFilterField extends AbstractFilterField
{
    use FilterFieldTraits\WithRequestFieldNameTrait,
        FilterFieldTraits\WithFilterFieldNameTrait,
        FilterFieldTraits\WithFilterRuleTrait;

    /**
     * @param array|string $requestValue
     * @return RegularFilterItem
     */
    protected function generateFilterItemResult($requestValue): RegularFilterItem
    {
        $filterField = $this->getFilterRule() . $this->getFilterFieldName();
        $result = new RegularFilterItem(
            [
                $filterField => is_array($requestValue) ? array_unique($requestValue) : $requestValue,
            ],
            $this
        );

        return $result;
    }

    /**
     * @param mixed $requestValue
     * @return RegularFilterItem|null
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

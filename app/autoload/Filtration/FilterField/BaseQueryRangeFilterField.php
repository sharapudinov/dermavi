<?php

namespace App\Filtration\FilterField;

use App\Filtration\Enum\RequestEnum;
use App\Filtration\FilterItem\QueryFilterItem;
use App\Filtration\Interfaces\FilterItemInterface;
use App\Filtration\Traits\FilterField as FilterFieldTraits;

/**
 * Class BaseQueryRangeFilterField
 * Описание правил фильтрации диапазонного поля для объекта Query
 *
 * @package App\Filtration\FilterField
 */
class BaseQueryRangeFilterField extends AbstractFilterField
{
    public const DEFAULT_FROM_VALUE = null;

    public const DEFAULT_TO_VALUE = null;

    use FilterFieldTraits\WithRequestFieldNameTrait,
        FilterFieldTraits\WithFilterFieldNameTrait,
        FilterFieldTraits\FilterRangeValueFormatterTrait;

    /**
     * @param $from
     * @param $to
     * @return QueryFilterItem
     */
    protected function generateFilterItemResult($from, $to): QueryFilterItem
    {
        $filterFieldRaw = $this->getFilterFieldName();
        $result = new QueryFilterItem(
            [
                '>=' . $filterFieldRaw => $from,
                '<=' . $filterFieldRaw => $to,
            ],
            $this
        );

        return $result;
    }

    /**
     * @param array|null $requestValue
     * @return QueryFilterItem|null
     */
    protected function generateFilterItem($requestValue): ?FilterItemInterface
    {
        $result = null;
        if (is_array($requestValue)) {
            $from = $requestValue[RequestEnum::RANGE_FIELD_FROM] ?? static::DEFAULT_FROM_VALUE;
            $to = $requestValue[RequestEnum::RANGE_FIELD_TO] ?? static::DEFAULT_TO_VALUE;
            if ($from !== null && $to !== null) {
                $this->formatFilterRangeValue($from, $to);
                $result = $this->generateFilterItemResult($from, $to);
            }
        }

        return $result;
    }
}

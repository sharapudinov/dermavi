<?php

namespace App\Filtration\FilterField;

use App\Filtration\Enum\RequestEnum;
use App\Filtration\FilterItem\RegularFilterItem;
use App\Filtration\Interfaces\FilterItemInterface;
use App\Filtration\Traits\FilterField as FilterFieldTraits;

/**
 * Class BaseRegularRangeFilterField
 * Описание правил фильтрации диапазонного поля для использования в фильтрах в виде массива
 *
 * @package App\Filtration\FilterField
 */
class BaseRegularRangeFilterField extends AbstractFilterField
{
    public const DEFAULT_FROM_VALUE = null;

    public const DEFAULT_TO_VALUE = null;

    use FilterFieldTraits\WithRequestFieldNameTrait,
        FilterFieldTraits\WithFilterFieldNameTrait,
        FilterFieldTraits\FilterRangeValueFormatterTrait;

    /**
     * @param $from
     * @param $to
     * @return RegularFilterItem
     */
    protected function generateFilterItemResult($from, $to): RegularFilterItem
    {
        $filterFieldRaw = $this->getFilterFieldName();
        $result = new RegularFilterItem(
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
     * @return RegularFilterItem|null
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

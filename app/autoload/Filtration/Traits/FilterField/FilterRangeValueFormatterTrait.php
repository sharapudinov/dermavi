<?php

namespace App\Filtration\Traits\FilterField;

/**
 * Trait FilterRangeValueFormatterTrait
 *
 * @package App\Filtration\Traits\FilterField
 */
trait FilterRangeValueFormatterTrait
{
    /**
     * Форматирование значений диапазонных фильтров
     *
     * @param int|float|null $from
     * @param int|float|null $to
     */
    protected function formatFilterRangeValue(&$from = null, &$to = null): void
    {
        if ($from === null || $to === null) {
            return;
        }
        if ($from > $to) {
            $_to = $to;
            $to = $from;
            $from = $_to;
        }
    }
}

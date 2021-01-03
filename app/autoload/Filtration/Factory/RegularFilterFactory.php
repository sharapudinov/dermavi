<?php

namespace App\Filtration\Factory;

use App\Filtration\Collection\FilterItemCollection;
use App\Filtration\Interfaces\FilterItemRegularInterface;

/**
 * Class RegularFilterFactory
 * Фабрика генерации обычного фильтра в виде массива для последующей передачи в методы для выборки
 *
 * @package App\Filtration\Factory
 *
 * @method array create
 */
class RegularFilterFactory extends AbstractFilterFactory
{
    /**
     * @return string
     */
    protected function getSupportedInstance(): string
    {
        return FilterItemRegularInterface::class;
    }

    /**
     * Генерация фильтра
     *
     * @param FilterItemCollection|FilterItemRegularInterface[] $filterItemCollection
     * @return array
     */
    protected function internalCreate(FilterItemCollection $filterItemCollection): array
    {
        $result = [];
        foreach ($filterItemCollection as $item) {
            $value = $item->getValue();
            if ($value !== null) {
                $result[] = $value;
            }
        }
        if ($result) {
            $result = array_merge(...$result);
        }

        return $result;
    }
}

<?php

namespace App\Filtration\FilterField;

use App\Filtration\Interfaces\FilterFieldInterface;
use App\Filtration\Interfaces\FilterItemInterface;
use App\Filtration\Interfaces\FilterRequestInterface;
use App\Filtration\Traits\FilterField\WithExcludedInFilterTrait;

/**
 * Class AbstractFilterField
 * Описание правил фильтрации поля
 *
 * @package App\Filtration\FilterField
 */
abstract class AbstractFilterField implements FilterFieldInterface
{
    use WithExcludedInFilterTrait;

    /**
     * Возвращает имя фильтруемого поля в запросе
     *
     * @return string
     */
    abstract public function getRequestFieldName(): string;

    /**
     * Возвращает имя фильтруемого поля
     *
     * @return string
     */
    abstract public function getFilterFieldName(): string;

    /**
     * @param mixed $requestValue
     * @return FilterItemInterface|null
     */
    abstract protected function generateFilterItem($requestValue): ?FilterItemInterface;

    /**
     * Возвращает фильтр для поля
     *
     * @param FilterRequestInterface $filterRequest
     * @return FilterItemInterface|null
     */
    public function getFilterItem(FilterRequestInterface $filterRequest): ?FilterItemInterface
    {
        $requestValue = $filterRequest->getValue($this->getRequestFieldName());
        if ($requestValue !== null) {
            return $this->generateFilterItem($requestValue);
        }

        return null;
    }
}

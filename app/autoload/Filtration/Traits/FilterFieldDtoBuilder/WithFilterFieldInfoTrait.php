<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder;

use App\Filtration\Model\FilterFieldInfo;

/**
 * Trait FilterFieldInfoGetterTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder
 */
trait WithFilterFieldInfoTrait
{
    /** @var FilterFieldInfo */
    protected $filterFieldInfo;

    /**
     * @return FilterFieldInfo
     */
    abstract protected function buildFilterFieldInfo(): FilterFieldInfo;

    /**
     * Возвращает параметры фильтруемого поля
     *
     * @return FilterFieldInfo
     */
    public function getFilterFieldInfo(): FilterFieldInfo
    {
        if ($this->filterFieldInfo === null) {
            $this->filterFieldInfo = $this->buildFilterFieldInfo();
        }

        return $this->filterFieldInfo;
    }

    /**
     * @param FilterFieldInfo $filterFieldInfo
     * @return static
     */
    public function setFilterFieldInfo(FilterFieldInfo $filterFieldInfo)
    {
        $this->filterFieldInfo = $filterFieldInfo;

        return $this;
    }
}

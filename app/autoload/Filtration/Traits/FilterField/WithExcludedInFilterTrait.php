<?php

namespace App\Filtration\Traits\FilterField;

/**
 * Trait WithExcludedInFilterTrait
 *
 * @package App\Filtration\Traits\FilterField
 */
trait WithExcludedInFilterTrait
{
    /** @var bool Нужно ли пропускать поле при генерации фильтра */
    protected $excludedInFilter = false;

    /**
     * @return bool
     */
    public function isExcludedInFilter(): bool
    {
        return $this->excludedInFilter;
    }

    /**
     * @param bool $excludedInFilter
     * @return static
     */
    public function setExcludedInFilter(bool $excludedInFilter)
    {
        $this->excludedInFilter = $excludedInFilter;

        return $this;
    }
}

<?php

namespace App\Filtration\Traits\FilterField;

/**
 * Trait WithFilterFieldNameTrait
 *
 * @package App\Filtration\Traits\FilterField
 */
trait WithFilterFieldNameTrait
{
    /** @var string Имя поля для фильтра */
    protected $filterFieldName = '';

    /**
     * @return string
     */
    public function getFilterFieldName(): string
    {
        return $this->filterFieldName;
    }

    /**
     * @param string $filterFieldName
     * @return static
     */
    public function setFilterFieldName(string $filterFieldName)
    {
        $this->filterFieldName = $filterFieldName;

        return $this;
    }
}

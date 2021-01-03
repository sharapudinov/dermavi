<?php

namespace App\Filtration\Traits\FilterField;

/**
 * Trait WithFilterRuleTrait
 *
 * @package App\Filtration\Traits\FilterField
 */
trait WithFilterRuleTrait
{
    /** @var string Тип фильтрации */
    protected $filterRule = '=';

    /**
     * @return string
     */
    public function getFilterRule(): string
    {
        return $this->filterRule;
    }

    /**
     * @param string $filterRule
     * @return static
     */
    public function setFilterRule(string $filterRule)
    {
        $this->filterRule = $filterRule;

        return $this;
    }
}

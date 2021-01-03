<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder;

/**
 * Trait WithAppliedFilterRequestValuesTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder
 */
trait WithAppliedFilterRequestValuesTrait
{
    /** @var array Значения, примененные в фильтре для поля */
    protected $appliedFilterRequestValues = [];

    /**
     * @param array $values
     * @return static
     */
    protected function setAppliedFilterRequestValues(array $values)
    {
        $this->appliedFilterRequestValues = $values;

        return $this;
    }

    /**
     * @param string $value
     * @return bool
     */
    protected function isAppliedFilterRequestValue(string $value): bool
    {
        return in_array($value, $this->appliedFilterRequestValues, false);
    }

    /**
     * @return array
     */
    protected function getAppliedFilterRequestValues(): array
    {
        return $this->appliedFilterRequestValues;
    }
}

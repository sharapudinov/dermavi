<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder;

/**
 * Trait WithFilterFieldDtoCodeTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder
 */
trait WithFilterFieldDtoCodeTrait
{
    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = '';

    /**
     * @return string
     */
    public function getFilterFieldDtoCode(): string
    {
        return $this->filterFieldDtoCode;
    }

    /**
     * @param string $filterFieldDtoCode
     * @return static
     */
    public function setFilterFieldName(string $filterFieldDtoCode)
    {
        $this->filterFieldDtoCode = $filterFieldDtoCode;

        return $this;
    }
}

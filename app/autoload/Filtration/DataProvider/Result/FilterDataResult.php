<?php

namespace App\Filtration\DataProvider\Result;

use App\Filtration\Dto\FieldDto;
use App\Filtration\Interfaces\FilterFieldDtoBuilderInterface;

/**
 * Class FilterDataResult
 *
 * @package App\Filtration\DataProvider
 */
class FilterDataResult
{
    /** @var FieldDto */
    protected $fieldDto;

    /** @var FilterFieldDtoBuilderInterface */
    protected $filterField;

    /**
     * @return FieldDto|null
     */
    public function getFieldDto(): ?FieldDto
    {
        return $this->fieldDto;
    }

    /**
     * @param FieldDto $fieldDto
     * @return static
     */
    public function setFieldDto(FieldDto $fieldDto)
    {
        $this->fieldDto = $fieldDto;

        return $this;
    }

    /**
     * @return FilterFieldDtoBuilderInterface|null
     */
    public function getFilterField(): ?FilterFieldDtoBuilderInterface
    {
        return $this->filterField;
    }

    /**
     * @param FilterFieldDtoBuilderInterface $filterField
     * @return static
     */
    public function setFilterField(FilterFieldDtoBuilderInterface $filterField)
    {
        $this->filterField = $filterField;

        return $this;
    }
}

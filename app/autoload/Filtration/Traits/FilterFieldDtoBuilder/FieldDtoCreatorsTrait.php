<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder;

use App\Filtration\Collection\VariantDtoCollection;
use App\Filtration\Dto\FieldDto;
use App\Filtration\Dto\FieldVariantDto;

/**
 * Trait FieldDtoCreatorsTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder
 */
trait FieldDtoCreatorsTrait
{
    /**
     * @return FieldDto
     */
    protected function createFieldDto(): FieldDto
    {
        return (new FieldDto())->setVariants($this->createFieldVariantDtoCollection());
    }

    /**
     * @return VariantDtoCollection
     */
    protected function createFieldVariantDtoCollection(): VariantDtoCollection
    {
        return new VariantDtoCollection();
    }

    /**
     * @return FieldVariantDto
     */
    protected function createFieldVariantDto(): FieldVariantDto
    {
        return new FieldVariantDto();
    }
}

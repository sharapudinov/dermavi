<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder;

use App\Filtration\Dto\FieldDto;

/**
 * Trait WithFieldDtoFinalizerTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder
 */
trait WithFieldDtoFinalizerTrait
{
    /** @var callable */
    protected $fieldDtoFinalizer;

    /**
     * @return callable|null
     */
    public function getFieldDtoFinalizer(): ?callable
    {
        return $this->fieldDtoFinalizer;
    }

    /**
     * Задает callback для финализации FieldDto.
     * N.B. Чтобы была возможность сохранения объекта в кеше, сюда лучше не передавать анонимные функции.
     *
     * @param callable $fieldDtoFinalizer
     * @return static
     */
    public function setFieldDtoFinalizer(callable $fieldDtoFinalizer)
    {
        $this->fieldDtoFinalizer = $fieldDtoFinalizer;

        return $this;
    }

    /**
     * @param FieldDto $fieldDto
     * @return FieldDto
     */
    protected function finalizeFieldDto(FieldDto $fieldDto): FieldDto
    {
        $fieldDtoFinalizer = $this->getFieldDtoFinalizer();
        if ($fieldDtoFinalizer) {
            $result = $fieldDtoFinalizer($fieldDto);
            if ($result instanceof FieldDto) {
                $fieldDto = $result;
            }
        }

        return $fieldDto;
    }
}

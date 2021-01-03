<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder;

use App\Filtration\Dto\FieldDto;

/**
 * Trait WithVariantItemTransformerTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder
 */
trait WithVariantItemTransformerTrait
{
    /** @var callable */
    protected $variantItemTransformer;

    /**
     * @return callable|null
     */
    public function getVariantItemTransformer(): ?callable
    {
        return $this->variantItemTransformer;
    }

    /**
     * Задает callback для преобразования массива, который потом будет использован для построения FieldVariantDto.
     * N.B. Чтобы была возможность сохранения объекта в кеше, сюда лучше не передавать анонимные функции.
     *
     * @param callable $variantItemTransformer
     * @return static
     */
    public function setVariantItemTransformer(callable $variantItemTransformer)
    {
        $this->variantItemTransformer = $variantItemTransformer;

        return $this;
    }

    /**
     * @param array $item
     * @param FieldDto $fieldDto
     * @return array
     */
    protected function transformVariantItem($item, FieldDto $fieldDto): array
    {
        $variantItemTransformer = $this->getVariantItemTransformer();
        if ($variantItemTransformer) {
            $item = (array)$variantItemTransformer($item, $fieldDto);
        }

        return $item;
    }
}

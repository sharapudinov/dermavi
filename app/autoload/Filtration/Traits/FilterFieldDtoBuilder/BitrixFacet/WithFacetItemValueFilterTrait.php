<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet;

/**
 * Trait WithFacetItemValueFilterTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet
 */
trait WithFacetItemValueFilterTrait
{
    /** @var callable */
    protected $facetItemsValuesFilter;

    /**
     * @return callable|null
     */
    public function getFacetItemsValuesFilter(): ?callable
    {
        return $this->facetItemsValuesFilter;
    }

    /**
     * Задает колбек для фильтрации "сырых" значений, полученных из фасетного индекса.
     * N.B. Чтобы была возможность сохранения объекта в кеше, сюда лучше не передавать анонимные функции.
     *
     * @param callable $facetItemsValuesFilter
     * @return static
     */
    public function setFacetItemsValuesFilter(callable $facetItemsValuesFilter)
    {
        $this->facetItemsValuesFilter = $facetItemsValuesFilter;

        return $this;
    }

    /**
     * @param array $values
     * @return array
     */
    protected function filterFacetItemsValues(array $values): array
    {
        $facetItemsValuesFilter = $this->getFacetItemsValuesFilter();
        if ($facetItemsValuesFilter) {
            $values = (array)$facetItemsValuesFilter($values);
        }

        return $values;
    }
}

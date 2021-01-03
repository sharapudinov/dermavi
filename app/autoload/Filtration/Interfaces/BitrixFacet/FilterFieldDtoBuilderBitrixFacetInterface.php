<?php

namespace App\Filtration\Interfaces\BitrixFacet;

use App\Filtration\Interfaces\FilterRequestInterface;
use App\Filtration\Interfaces\FilterFieldDtoBuilderInterface;
use Bitrix\Iblock\PropertyIndex\Facet;
use Throwable;

/**
 * Interface FilterFieldDtoBuilderBitrixFacetInterface
 *
 * @package App\Filtration\Interfaces\BitrixFacet
 */
interface FilterFieldDtoBuilderBitrixFacetInterface extends FilterFieldDtoBuilderInterface
{
    /**
     * @param Facet $facet
     * @param FilterRequestInterface $filterRequest
     * @return array Примененные значения
     * @throws Throwable
     */
    public function applyBitrixFacetFilterRequest(Facet $facet, FilterRequestInterface $filterRequest): array;

    /**
     * Является ли свойством фильтруемое поле
     *
     * @return bool
     */
    public function isFilterFieldEntityPropertyType(): bool;
}

<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder\Getter;

use App\Filtration\Helper\PropertiesHelper;

/**
 * Trait IBlockPropertyInfoGetterTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder\Getter
 */
trait IBlockPropertyInfoGetterTrait
{
    /**
     * @param int $propertyId
     * @return array
     */
    protected function getIBlockPropertyInfoById(int $propertyId): array
    {
        return PropertiesHelper::getPropertyArray($propertyId);
    }
}

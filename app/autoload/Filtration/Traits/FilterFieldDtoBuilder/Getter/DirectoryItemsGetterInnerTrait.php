<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder\Getter;

use App\Filtration\Exception\ArgumentEmptyException;
use App\Filtration\Exception\LogicException;
use App\Filtration\Relation\RelationValuesGetter;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

/**
 * Trait DirectoryItemsGetterInnerTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder\Getter
 */
trait DirectoryItemsGetterInnerTrait
{
    /**
     * @return RelationValuesGetter
     */
    abstract protected function getDirectoryRelationValuesGetter(): RelationValuesGetter;

    /**
     * Возвращает значения справочника по кодам
     *
     * @param int $propertyId
     * @param array $xmlIdList
     * @param int $queryChunkSize
     * @return array
     * @throws LogicException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    protected function getDirectoryItems(int $propertyId, array $xmlIdList, int $queryChunkSize = 0): array
    {
        try {
            $result = $this->getDirectoryRelationValuesGetter()->getDirectoryItems(
                $propertyId,
                $xmlIdList,
                $queryChunkSize
            );
        } catch (ArgumentEmptyException $exception) {
            $result = [];
        }

        return $result;
    }
}

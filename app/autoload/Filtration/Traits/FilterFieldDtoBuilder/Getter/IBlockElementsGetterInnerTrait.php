<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder\Getter;

use App\Filtration\Exception\ArgumentEmptyException;
use App\Filtration\Relation\RelationValuesGetter;

/**
 * Trait IBlockElementsGetterInnerTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder\Getter
 */
trait IBlockElementsGetterInnerTrait
{
    /**
     * @return RelationValuesGetter
     */
    abstract protected function getIBlockElementRelationValuesGetter(): RelationValuesGetter;

    /**
     * @param array $elementsIdList
     * @param int $iblockId
     * @param int $queryChunkSize
     * @return array
     */
    protected function getIBlockElementsList(array $elementsIdList, int $iblockId = 0, int $queryChunkSize = 0): array
    {
        try {
            $result = $this->getIBlockElementRelationValuesGetter()->getElementsList(
                $elementsIdList,
                $iblockId,
                $queryChunkSize
            );
        } catch (ArgumentEmptyException $exception) {
            $result = [];
        }

        return $result;
    }
}

<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder\Getter;

use App\Filtration\Exception\ArgumentEmptyException;
use App\Filtration\Relation\RelationValuesGetter;

/**
 * Trait IBlockSectionsGetterInnerTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder\Getter
 */
trait IBlockSectionsGetterInnerTrait
{
    /**
     * @return RelationValuesGetter
     */
    abstract protected function getIBlockSectionRelationValuesGetter(): RelationValuesGetter;

    /**
     * @param array $sectionsIdList
     * @param int $iblockId
     * @param int $queryChunkSize
     * @return array
     */
    protected function getIBlockSectionsList(array $sectionsIdList, int $iblockId = 0, int $queryChunkSize = 0): array
    {
        try {
            $result = $this->getIBlockSectionRelationValuesGetter()->getSectionsList(
                $sectionsIdList,
                $iblockId,
                $queryChunkSize
            );
        } catch (ArgumentEmptyException $exception) {
            $result = [];
        }

        return $result;
    }
}

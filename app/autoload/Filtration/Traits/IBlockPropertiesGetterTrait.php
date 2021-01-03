<?php

namespace App\Filtration\Traits;

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use CIBlockProperty;
use CIBlockSectionPropertyLink;
use Exception;

/**
 * Trait IBlockElementsGetterTrait
 *
 * @package App\Filtration\Traits
 */
trait IBlockPropertiesGetterTrait
{
    /**
     * @param int $iblockId
     * @param int $sectionId
     * @param int $cacheTime
     * @return array
     */
    protected static function getIBlockSectionSmartPropertiesData(
        int $iblockId,
        int $sectionId = 0,
        int $cacheTime = 43200
    ): array
    {
        /** @todo Кеширование */
        $list = [];
        try {
            Loader::includeModule('iblock');
        } catch (Exception $exception) {
            // ignore
        }

        foreach (CIBlockSectionPropertyLink::GetArray($iblockId, $sectionId) as $propertyId => $sectionPropInfo) {
            if ($sectionPropInfo['SMART_FILTER'] !== 'Y') {
                continue;
            }

            if ($sectionPropInfo['ACTIVE'] === 'N') {
                continue;
            }
            $property = static::getIBlockPropertyArray($propertyId);
            if ($property) {
                //$list[$property['ID']] = $property;
                //foreach ($sectionPropInfo as $key => $value) {
                //    $list[$property['ID']]['SECTION_INFO_' . $key] = $value;
                //}
                $list[$property['ID']] = array_merge($sectionPropInfo, $property);
            }
        }

        return $list;
    }

    /**
     * @param int $propertyId
     * @param int $iblockId
     * @param bool $useCache
     * @return array
     */
    protected static function getIBlockPropertyArray(int $propertyId, int $iblockId = 0, bool $useCache = true): array
    {
        $result = CIBlockProperty::GetPropertyArray($propertyId, $iblockId, $useCache) ?: [];
        if ($result && $result['USER_TYPE_SETTINGS'] !== '') {
            $result['USER_TYPE_SETTINGS'] = unserialize($result['USER_TYPE_SETTINGS'], ['allowed_classes' => false]);
        }

        return $result;
    }

    /**
     * @param int $iblockId
     * @param int $cacheTime
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    protected static function getIBlockProperties(int $iblockId, int $cacheTime = 43200): array
    {
        $list = [];
        try {
            Loader::includeModule('iblock');
        } catch (Exception $exception) {
            // ignore
        }
        $iterator = PropertyTable::getList(
            [
                'filter' => [
                    '=IBLOCK_ID' => $iblockId,
                ],
                'cache' => [
                    'ttl' => $cacheTime,
                ],
                'select' => [
                    'ID', 'CODE',
                ],
            ]
        );
        while ($item = $iterator->fetch()) {
            $list[mb_strtoupper($item['CODE'])] = $item;
        }

        return $list;
    }
}

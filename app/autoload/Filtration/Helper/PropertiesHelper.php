<?php

namespace App\Filtration\Helper;

use App\Filtration\Traits\IBlockPropertiesGetterTrait;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Exception;

/**
 * Class PropertiesHelper
 *
 * @package App\Filtration\IBlock
 */
final class PropertiesHelper
{
    use IBlockPropertiesGetterTrait;

    /** @var array */
    private static $runtimeCache;

    /**
     * @return int
     */
    private static function getCacheTime(): int
    {
        return 43200;
    }

    /**
     * Возвращает id свойства по его символьному коду
     *
     * @param int $iblockId
     * @param string $propCode
     * @return int
     */
    public static function getPropertyId(int $iblockId, string $propCode): int
    {
        $propCode = mb_strtoupper(trim($propCode));
        if ($iblockId <= 0 || $propCode === '') {
            return 0;
        }

        if (!isset(self::$runtimeCache['propertyId'][$iblockId])) {
            try {
                self::loadIBlockProperties($iblockId);
            } catch (Exception $exception) {
                // ignore
            }
        }

        return self::$runtimeCache['getPropertyId'][$iblockId][$propCode] ?? 0;
    }

    /**
     * @param int $propertyId
     * @return array
     */
    public static function getPropertyArray(int $propertyId): array
    {
        return self::getIBlockPropertyArray($propertyId);
    }

    /**
     * Возвращает набор свойств элементов инфоблока с полями для "умного фильтра"
     *
     * @param int $iblockId
     * @param int $sectionId
     * @return array
     */
    public static function getIBlockSectionSmartProperties(int $iblockId, int $sectionId = 0): array
    {
        if (!isset(self::$runtimeCache['getSmartProperties'][$iblockId][$sectionId])) {
            try {
                self::loadIBlockSectionSmartProperties($iblockId, $sectionId);
            } catch (Exception $exception) {
                // ignore
            }
        }

        return self::$runtimeCache['getSmartProperties'][$iblockId][$sectionId] ?? [];
    }

    /**
     * Возвращает свойство элемента инфоблока, дополненное полями для "умного фильтра"
     *
     * @param int $propertyId
     * @param int $iblockId
     * @param int $sectionId
     * @return array
     */
    public static function getIBlockSectionSmartProperty(int $propertyId, int $iblockId, int $sectionId = 0): array
    {
        return self::getIBlockSectionSmartProperties($iblockId, $sectionId)[$propertyId] ?? [];
    }

    /**
     * @param int $iblockId
     * @param int $sectionId
     */
    private static function loadIBlockSectionSmartProperties(int $iblockId, int $sectionId = 0): void
    {
        self::$runtimeCache['getSmartProperties'][$iblockId][$sectionId] = self::getIBlockSectionSmartPropertiesData(
            $iblockId,
            $sectionId,
            self::getCacheTime()
        );
    }

    /**
     * @param int $iblockId
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private static function loadIBlockProperties(int $iblockId): void
    {
        self::$runtimeCache['getPropertyId'][$iblockId] = [];
        foreach (self::getIBlockProperties($iblockId, self::getCacheTime()) as $item) {
            self::$runtimeCache['getPropertyId'][$iblockId][mb_strtoupper($item['CODE'])] = (int)$item['ID'];
        }
    }
}

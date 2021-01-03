<?php

namespace App\Helpers\IBlock;

use Bitrix\Iblock\SectionTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Exception;

/**
 * Class SectionHelper
 *
 * @package App\Helpers\IBlock
 */
final class SectionHelper
{
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
     * Возвращает id секции по ее символьному коду
     *
     * @param string $sectionCode
     * @param int $iblockId
     * @return int
     */
    public static function getSectionId(string $sectionCode, int $iblockId = 0): int
    {
        $sectionId = 0;
        if ($sectionCode === '') {
            return $sectionId;
        }

        if (!isset(self::$runtimeCache['sectionId'][$sectionCode][$iblockId])) {
            try {
                self::loadSectionId($sectionCode, $iblockId);
            } catch (Exception $exception) {
                // ignore
            }
        }

        return self::$runtimeCache['sectionId'][$sectionCode][$iblockId] ?? 0;
    }

    /**
     * @param string $sectionCode
     * @param int $iblockId
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private static function loadSectionId(string $sectionCode, int $iblockId): void
    {
        self::$runtimeCache['sectionId'][$sectionCode][$iblockId] = 0;

        try {
            Loader::includeModule('iblock');
        } catch (Exception $exception) {
            // ignore
        }

        $query = (SectionTable::query())->setSelect(['ID'])->addFilter('=CODE', $sectionCode)->setLimit(1);
        if ($iblockId > 0) {
            $query->addFilter('IBLOCK_ID', $iblockId);
        }
        $query->setCacheTtl(self::getCacheTime())->cacheJoins(true);
        if ($item = $query->exec()->fetch()) {
            self::$runtimeCache['sectionId'][$sectionCode][$iblockId] = (int)$item['ID'];
        }
    }
}

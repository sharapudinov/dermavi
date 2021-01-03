<?php

namespace App\Filtration\Helper;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\ORM\Entity;

/**
 * Class HighloadblockHelper
 *
 * @package App\Filtration\IBlock
 */
final class HighloadblockHelper
{
    /** @var array */
    protected static $highloadBlocksCache;

    /** @var Entity[] */
    protected static $hlEntities = [];

    /**
     * @param string $tableName
     * @return Entity
     * @throws ArgumentException
     * @throws ObjectNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getHighloadBlockEntity(string $tableName): Entity
    {
        if (!isset(static::$hlEntities[$tableName])) {
            static::$hlEntities[$tableName] = static::compileEntity($tableName);
        }

        return self::$hlEntities[$tableName];
    }

    /**
     * @param string $tableName
     * @return Entity
     * @throws ArgumentException
     * @throws ObjectNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    protected static function compileEntity(string $tableName): Entity
    {
        $highloadBlockTableData = static::getHighloadBlockTableData($tableName);
        if (!$highloadBlockTableData) {
            throw new ObjectNotFoundException(
                'Table ' . $tableName . ' not found'
            );
        }

        return HighloadBlockTable::compileEntity($highloadBlockTableData);
    }

    /**
     * @param string $tableName
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    protected static function getHighloadBlockTableData(string $tableName): array
    {
        if (static::$highloadBlocksCache === null) {
            static::$highloadBlocksCache = [];
            $iterator = HighloadBlockTable::getList(
                [
                    'cache' => [
                        'ttl' => 86400,
                    ],
                ]
            );
            while ($item = $iterator->fetch()) {
                static::$highloadBlocksCache[$item['TABLE_NAME']] = $item;
            }
        }

        return static::$highloadBlocksCache[$tableName] ?? [];
    }
}

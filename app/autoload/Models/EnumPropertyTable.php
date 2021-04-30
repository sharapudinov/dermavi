<?php

namespace App\Models;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;

/**
 * Class EnumPropertyTable
 * @package App\Models
 */
class EnumPropertyTable extends DataManager
{
    public static function getTableName()
    {
        return 'b_iblock_property_enum';
    }

    public static function getMap()
    {
        $map = [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                ]
            ),
            new IntegerField(
                'PROPERTY_ID',
                [
                ]
            ),
            new IntegerField(
                'SORT',
                [
                ]
            ),
            new StringField(
                'VALUE',
                [
                ]
            ),
            new StringField(
                'XML_ID',
                [
                ]
            ),
            new StringField(
                'TMP_ID',
                [
                ]
            ),
        ];

        return $map;
    }
}

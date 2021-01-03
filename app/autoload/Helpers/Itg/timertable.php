<?php

namespace App\Helpers\Itg;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;


/**
 * Class TimerTable
 *
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> last_time datetime optional
 * </ul>
 *
 * @package Bitrix\Timer
 **/

class TimerTable extends Main\ORM\Data\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'my_timer';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'id' => array(
                'data_type' => 'integer',
                'primary' => true,
                'title' => 'id'
            ),
            'last_time' => array(
                'data_type' => 'datetime',
                'title' => ' time'
            ),
        );
    }
}
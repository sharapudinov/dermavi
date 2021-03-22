<?php

namespace Greensight\Geo\Entity\GeoLite2;

use Bitrix\Main\Entity\DataManager;

class IpTable extends DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
        return 'greensight_geo_geolite2_base';
    }

    public static function getMap()
    {
        return [
            'ID' => [
                'data_type'    => 'integer',
                'primary'      => true,
                'autocomplete' => true,
                'title'        => 'ID'
            ],
            'IP1'      => [
                'data_type' => 'string',
                'required'  => true
            ],
            'IP2'      => [
                'data_type' => 'string',
                'required'  => true
            ],
            'COUNTRY'  => [
                'data_type' => 'string',
            ],
            'LONG_IP1' => [
                'data_type' => 'integer',
            ],
            'LONG_IP2' => [
                'data_type' => 'integer',
            ],
            'CITY_ID'  => [
                'data_type' => 'integer',
            ],
            'NETWORK'      => [
                'data_type' => 'string',
            ],
            'LAT'      => [
                'data_type' => 'float',
            ],
            'LNG'      => [
                'data_type' => 'float',
            ],
            'ACCURACY_RADIUS'      => [
                'data_type' => 'string',
            ],
            'CITY'     => [
                'data_type' => 'Greensight\Geo\Entity\GeoLite2\Cities',
                'reference' => [
                    '=this.CITY_ID' => 'ref.CITY_ID',
                ],
                'join_type' => "inner"
            ],
        ];
    }
}

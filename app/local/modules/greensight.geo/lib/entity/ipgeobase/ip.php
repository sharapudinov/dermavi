<?php

namespace Greensight\Geo\Entity\IPGeobase;

use Bitrix\Main\Entity\DataManager;

class IpTable extends DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
        return 'greensight_geo_ipgeobase_base';
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
            'CITY'     => [
                'data_type' => 'Greensight\Geo\Entity\IPGeobase\Cities',
                'reference' => [
                    '=this.CITY_ID' => 'ref.CITY_ID',
                ],
                'join_type' => "inner"
            ],
            // virtual
            /*'NAME' => [
                'data_type' => 'Bitrix\Sale\Location\Name\Location',
                'reference' => [
                    '=this.CITY' => 'ref.NAME',
                ],
                'join_type' => "inner"
            ],*/
        ];
    }
}

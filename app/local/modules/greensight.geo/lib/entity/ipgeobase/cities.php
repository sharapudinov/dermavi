<?php

namespace Greensight\Geo\Entity\IPGeobase;

use Bitrix\Main\Entity\DataManager;

class CitiesTable extends DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }
    
    public static function getTableName()
    {
        return 'greensight_geo_ipgeobase_cities';
    }
    
    public static function getMap()
    {
        return [
            'ID'       => [
                'data_type'    => 'integer',
                'primary'      => true,
                'autocomplete' => true,
                'title'        => 'ID'
            ],
            'CITY'     => [
                'data_type' => 'string',
                'required'  => true
            ],
            'REGION'   => [
                'data_type' => 'string',
                //'required' => true
            ],
            'DISTRICT' => [
                'data_type' => 'string',
                //'required' => true
            ],
            'CITY_ID'  => [
                'data_type' => 'integer',
            ],
            'LAT'      => [
                'data_type' => 'float',
            ],
            'LNG'      => [
                'data_type' => 'float',
            ],
            // virtual
            'NAME'     => [
                'data_type' => 'Bitrix\Sale\Location\Name\Location',
                'reference' => [
                    '=this.CITY' => 'ref.NAME',
                ],
                'join_type' => "inner"
            ],
            'LOCATION' => [
                'data_type' => 'Bitrix\Sale\Location\Location',
                'reference' => [
                    '=this.NAME.LOCATION_ID' => 'ref.ID',
                ],
                'join_type' => "inner"
            ],
            'IP' => [
                'data_type' => 'Greensight\Geo\Entity\IPGeobase\Ip',
                'reference' => [
                    '=this.CITY_ID' => 'ref.CITY_ID',
                ],
                'join_type' => "inner"
            ],
        ];
    }
}

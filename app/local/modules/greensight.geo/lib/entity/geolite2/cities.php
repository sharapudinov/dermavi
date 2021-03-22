<?php

namespace Greensight\Geo\Entity\GeoLite2;

use Bitrix\Main\Entity\DataManager;

class CitiesTable extends DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }
    
    public static function getTableName()
    {
        return 'greensight_geo_geolite2_cities';
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
            'CITY_ID'  => [
                'data_type' => 'integer',
            ],
            'LOCALE_CODE'   => [
                'data_type' => 'string',
                //'required' => true
            ],
            'CONTINENT_CODE'   => [
                'data_type' => 'string',
                //'required' => true
            ],
            'CONTINENT_NAME'   => [
                'data_type' => 'string',
                //'required' => true
            ],
            'COUNTRY_ISO_CODE'   => [
                'data_type' => 'string',
                //'required' => true
            ],
            'COUNTRY_NAME'   => [
                'data_type' => 'string',
                //'required' => true
            ],
            'TIME_ZONE'   => [
                'data_type' => 'string',
                //'required' => true
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
                'data_type' => 'Greensight\Geo\Entity\GeoLite2\Ip',
                'reference' => [
                    '=this.CITY_ID' => 'ref.CITY_ID',
                ],
                'join_type' => "inner"
            ],
        ];
    }
}

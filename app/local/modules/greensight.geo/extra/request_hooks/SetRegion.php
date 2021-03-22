<?php

namespace App\RequestHooks;

use Bitrix\Main\Loader;
use Greensight\Geo\Location;

/**
 * Данный хук устанавливает глобальную константу REGION равной региону пользователя
 * Регион пользователя берется либо из кук, либо геолокируется по IP.
 */
class SetRegion
{
    /**
     * TBD
     *
     * ID/Код региона по-умолчанию в случае если не удалось найти его через геолокацию.
     *
     * @return int|string
     */
    private static function getDefaultRegion()
    {
        return 'spb';
    }
    
    /**
     * TBD
     *
     * Получение ID/Кода региона по идентификатору города из базы геолокации.
     *
     * @param int|string $cityId
     * @return int|string
     */
    private static function getRegionByCityId($cityId)
    {
        return 'spb';
    }

    /**
     * Основной обработчик хука.
     */
    public static function handle()
    {
        $region = $_COOKIE["region"];

        if (!$region) {
            if (Loader::includeModule('greensight.geo')) {
                $info = Location::getInstance()->getInfoFromDatabase();
            };
            $region = !empty($info['city_id']) ? static::getRegionByCityId($info['city_id']) : static::getDefaultRegion();
            $ttl = time() + 30 * 24 * 3600;
            setcookie('region', $region, $ttl, '/');
            setcookie('region_requires_confirmation', '1', $ttl, '/');
            $_COOKIE["region"] = $region;
            $_COOKIE["region_requires_confirmation"] = 1;
        }

        define('REGION', $region);
    }
}

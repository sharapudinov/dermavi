<?php

namespace Greensight\Geo;

use Bitrix\Main\Loader;
use Bitrix\Sale\Location\LocationTable;
use Greensight\Geo\Providers\IpGeoBaseProvider;
use Greensight\Geo\Providers\ProviderInterface;

class Location
{
    /**
     * @var Location
     */
    protected static $instance;

    /**
     * @var ProviderInterface
     */
    protected $provider;

    public function __construct()
    {
        $this->provider = new IpGeoBaseProvider();
    }

    /**
     * @return Location
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Получение местоположения по ip адресу.
     * Если ip адрес не передан, то берется из $_SERVER["REMOTE_ADDR"]
     *
     * Доступные параметры в $params:
     * $params['include_bitrix_location'] = true - добавить Битриксовые Location в результат.
     * $paras['cache'] = 30 - закэшировать результат поиска по этому IP на 30 минут
     *
     * @param null|string $ip
     * @param array $params
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     */
    public function getInfoFromDatabase($ip = null, $params = [])
    {
        if (is_null($ip)) {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        $location = $this->getProvider()->search($ip, $params);
        if (!empty($params['include_bitrix_location'])) {
            $location['bitrix_location'] = $this->getBitrixLocation($location, $params);
        }
        
        return $location;
    }
    
    /**
     * @param array $location
     * @param array $params
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     */
    protected function getBitrixLocation($location, $params = [])
    {
        if (empty($location['city'])) {
            return [];
        }

        $cache = (int) $params['cache'];
        $params['filter'] = ['NAME.NAME' => $location['city']];
        if ($cache) {
            $params['cache'] = $cache * 60;
        }

        if (!Loader::includeModule('sale')) {
            return [];
        }

        return LocationTable::getList($params)->fetch();
    }
    
    /**
     * Получаем список местоположений по-умолчанию
     *
     * @param  array $params
     * @param  string $lang
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     */
    public function getDefaultBitrixLocations($params = [], $lang = 'ru')
    {
        if (!Loader::includeModule('sale')) {
            return [];
        }

        $params['filter'] = ['>DEFAULT_SITE.LOCATION_CODE' => 0, 'NAME.LANGUAGE_ID' => $lang];

        return LocationTable::getList($params)->fetchAll();
    }

//    /**
//     * Получение местоположения из кук.
//     *
//     * @return array
//     */
//    public function getInfoFromCookies()
//    {
//        if (!$_COOKIE['gs_location'] || empty($_COOKIE['gs_location'])) return false;
//
//        $info = json_decode($_COOKIE['gs_location'], true);
//        return $info ?: false;
//    }
//
//    /**
//     * Получение местоположения из кук.
//     *
//     * @return array
//     */
//    public function saveInfoToCookies($info, $days = 30)
//    {
//        $info = json_encode($info);
//        $exp = time()+60*60*24*$days;
//        setcookie('gs_location', $info, $exp);
//        $_COOKIE['gs_location'] = $info;
//    }
//
//    /**
//     * Получение местоположения из кук.
//     *
//     * @return array
//     */
//    public function getBxInfoFromCookies($params = [])
//    {
//        $info = $_COOKIE['gs_bx_location'];
//        if (!$info || empty($info)) return false;
//        if (!Loader::includeModule('sale')) return false;
//
//        $params['filter'] = ['CODE' => $info, 'NAME.LANGUAGE_ID' => 'ru'];
//        $params['cache'] = $params['cache'] ?: 3600;
//        return LocationTable::getList($params)->fetch();
//    }
//
//    /**
//     * Получение местоположения из кук.
//     *
//     * @return array
//     */
//    public function saveBxInfoToCookies($info, $days = 30)
//    {
//        $info = json_encode($info);
//        $exp = time()+60*60*24*$days;
//        setcookie('gs_bx_location', $info, $exp);
//        $_COOKIE['gs_bx_location'] = $info;
//    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function setProvider(ProviderInterface $provider)
    {
        $this->provider = $provider;

        return $this;
    }
}

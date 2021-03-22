<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Greensight\Geo\ExampleTable;

Loc::loadMessages(__FILE__);

class greensight_geo extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();
        
        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        
        $this->MODULE_ID = 'greensight.geo';
        $this->MODULE_NAME = Loc::getMessage('GREENSIGHT_GEO_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('GREENSIGHT_GEO_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('GREENSIGHT_GEO_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'https://greensight.ru';
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
    }

    public function doUninstall()
    {
        $this->uninstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installDB()
    {
        if (!Loader::includeModule($this->MODULE_ID)) {
            return;
        }
        $connection = $connection = Application::getConnection();

        $connection->query('DROP TABLE IF EXISTS greensight_geo_ipgeobase_base');
        $connection->query('DROP TABLE IF EXISTS greensight_geo_ipgeobase_cities');

        $sql = "
        CREATE TABLE `greensight_geo_ipgeobase_base` (
          `id` INT NOT NULL AUTO_INCREMENT,
          `long_ip1` bigint(20) NOT NULL,
          `long_ip2` bigint(20) NOT NULL,
          `ip1` varchar(16) NOT NULL,
          `ip2` varchar(16) NOT NULL,
          `country` varchar(2) NOT NULL,
          `city_id` int(10) NOT NULL,
          PRIMARY KEY (id),
          INDEX ix_ips (`long_ip1`,`long_ip2`)
        );";
        $connection->query($sql);
        
        $sql = "CREATE TABLE `greensight_geo_ipgeobase_cities` (
          `id` INT NOT NULL AUTO_INCREMENT,
          `city_id` int(10) NOT NULL,
          `city` varchar(128) NOT NULL,
          `region` varchar(128) NOT NULL,
          `district` varchar(128) NOT NULL,
          `lat` float NOT NULL,
          `lng` float NOT NULL,
          PRIMARY KEY (id),
          INDEX ix_city_id (`city_id`)
        )";
        $connection->query($sql);

        $connection->query('DROP TABLE IF EXISTS greensight_geo_geolite2_base');
        $connection->query('DROP TABLE IF EXISTS greensight_geo_geolite2_cities');

        $sql = "
        CREATE TABLE `greensight_geo_geolite2_base` (
          `id` INT NOT NULL AUTO_INCREMENT,
          `long_ip1` bigint(20) NOT NULL,
          `long_ip2` bigint(20) NOT NULL,
          `ip1` varchar(16) NOT NULL,
          `ip2` varchar(16) NOT NULL,
          `country` varchar(2) NOT NULL,
          `city_id` int(10) NOT NULL,

          `network` varchar(20) NOT NULL,
          `lat` float NOT NULL,
          `lng` float NOT NULL,
          `postal_code` varchar(20) NOT NULL,
          `accuracy_radius` varchar(10),
          PRIMARY KEY (id),
          INDEX ix_ips (`long_ip1`,`long_ip2`)
        );";
        $connection->query($sql);
        
        $sql = "CREATE TABLE `greensight_geo_geolite2_cities` (
          `id` INT NOT NULL AUTO_INCREMENT,
          `city_id` int(10) NOT NULL,
          `city` varchar(128) NOT NULL,
          `region` varchar(128) NOT NULL,

          `locale_code` varchar(2) NOT NULL,
          `continent_code` varchar(2) NOT NULL,
          `continent_name` varchar(128) NOT NULL,
          `country_iso_code` varchar(2) NOT NULL,
          `country_name` varchar(128) NOT NULL,
          `time_zone` varchar(128) NOT NULL,
          PRIMARY KEY (id),
          INDEX ix_city_id (`city_id`)
        )";
        $connection->query($sql);
    }

    public function uninstallDB()
    {
        if (!Loader::includeModule($this->MODULE_ID)) {
            return;
        }

        $connection = Application::getConnection();
        $connection->query('DROP TABLE IF EXISTS greensight_geo_ipgeobase_base');
        $connection->query('DROP TABLE IF EXISTS greensight_geo_ipgeobase_cities');
        
        $connection->query('DROP TABLE IF EXISTS greensight_geo_geolite2_base');
        $connection->query('DROP TABLE IF EXISTS greensight_geo_geolite2_cities');
    }
}

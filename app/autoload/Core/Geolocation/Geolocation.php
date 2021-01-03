<?php

namespace App\Core\Geolocation;

use App\Core\Geolocation\Entity\GeolocationEntity;
use App\Helpers\GeolocationHelper;
use App\Helpers\StringHelper;
use App\Helpers\TTL;
use App\Models\Catalog\HL\StoneLocation;
use Bitrix\Main\Loader;
use Greensight\Geo\Location;

Loader::includeModule('greensight.geo');

/**
 * Класс для работы с геолокацией пользователя
 * Class Geolocation
 * @package App\Core\Geolocation
 */
class Geolocation extends Location
{
    /** @var GeolocationEntity $userLocation - Информация о местоположении пользователя */
    private static $userLocation;

    /**
     * Получаем информацию о геолокации пользователя
     *
     * @return GeolocationEntity
     */
    public static function getUserLocation(): GeolocationEntity
    {
        if (!static::$userLocation) {
            /** @var string $cacheKey - Ключ для кеширования */
            $cacheKey = get_class_name_without_namespace(self::class)
                . '_user_' . StringHelper::getUserIpWithUnderscore();

            static::$userLocation = cache($cacheKey, TTL::DAY, function () {
                /** @var array $userLocation - Информация о геолокации пользователя */
                $userLocation = parent::getInstance()->getInfoFromDatabase();

                /** @var float|null $distanceToArkh - Расстояние до Архангельска */
                $distanceToArkh = null;
                /** @var float|null $distanceToYakutia - Расстояние до Якутии */
                $distanceToYakutia = null;

                if ($userLocation['lat'] && $userLocation['lon']) {
                    /** @var StoneLocation $arkhangelsk - Информация о городе "Архангельск" */
                    $arkhangelsk = StoneLocation::filter(['=UF_NAME_EN' => GeolocationHelper::ARKHANGELSK_SYM_CODE])
                        ->first();
                    $distanceToArkh = $arkhangelsk->getDistance(
                        (float) $userLocation['lat'],
                        (float) $userLocation['lon']
                    );

                    /** @var StoneLocation $yakutia - Информация о регионе "Якутия" */
                    $yakutia = StoneLocation::filter(['=UF_NAME_EN' => GeolocationHelper::YAKUTIA_SYM_CODE])->first();
                    $distanceToYakutia = $yakutia->getDistance(
                        (float) $userLocation['lat'],
                        (float) $userLocation['lon']
                    );
                }

                return new GeolocationEntity($userLocation, $distanceToArkh, $distanceToYakutia);
            });
        }

        return static::$userLocation;
    }
}

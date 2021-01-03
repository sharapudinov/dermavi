<?php

namespace App\Core\SiteVersion;

use App\Core\Geolocation\Geolocation;
use App\Core\System\LanguageSetter;
use App\Helpers\StringHelper;
use App\Helpers\TTL;

/**
 * Класс, реализующий логику определения языковой версии сайта для неавторизованного пользователя
 * Class NotAuthorizedUser
 *
 * @package App\Core\SiteVersion
 */
class NotAuthorizedUser implements SiteVersionInterface
{
    /**
     * Возвращает идентификатор языковой версии сайта для текущего пользователя
     *
     * @return null|string
     */
    public function getUserSiteVersion(): ?string
    {
        /** @var string $ipWithoutPoints - IP адрес пользователя с точками, замененными подчеркиваниями */
        $ipWithoutPoints = StringHelper::getUserIpWithUnderscore();
        return cache(
            get_default_cache_key(self::class) . '_' . $ipWithoutPoints,
            TTL::DAY,
            function () {
                /** @var array|array[] $countriesCodesMapping - Массив, описывающий соотношение стран к языкам */
                $countriesCodesMapping = LanguageSetter::getCountriesCodes();

                /** @var string $countryCode - Код страны, в которой находится пользователь */
                $countryCode = strtolower(Geolocation::getUserLocation()->getCountryCode());

                return array_key_exists($countryCode, $countriesCodesMapping)
                    ? $countryCode
                    : null;
            }
        );
    }
}

<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use CSite;

/**
 * Класс-хелпер для работы с мультисайтовостью
 * Class SiteHelper
 * @package App\Helpers
 */
class SiteHelper
{
    /** @var string|null Переопределенный идентификатор текущего сайта */
    private static $siteId;

    /**
     * Получаем идентификатор активной версии сайта
     *
     * @return string
     */
    public static function getSiteId(): string
    {
        return self::$siteId ?? SITE_ID;
    }

    /**
     * Устанавливает текущий сайт.
     *
     * @param string|null $siteId
     */
    public static function setSiteId(string $siteId = null): void
    {
        self::$siteId = $siteId;
    }

    /**
     * Получаем путь до шаблона сайта
     *
     * @return string
     */
    public static function getSiteTemplatePath(): string
    {
        return SITE_TEMPLATE_PATH;
    }

    /**
     * Получить список сайтов
     *
     * @param $key = 'LID' Поле, которое будет использовано в качестве ключа
     * @return Collection
     */
    public static function getSites(string $key = 'LID'): Collection
    {
        return cache('App\Helpers\SiteHelper\getSites', TTL::MONTH, function () {
            $sites = new Collection();

            $rsSites = CSite::GetList($by = 'sort', $order = 'asc', []);
            while ($site = $rsSites->Fetch()) {
                $sites->push($site);
            }

            return $sites;
        })->keyBy($key);
    }

    /**
     * Получить текущий сайт
     *
     * @return array
     */
    public static function getCurrentSite(): array
    {
        return (array) self::getSites()[self::getSiteId()];
    }

    /**
     * Получить язык сайта по его символьному коду
     *
     * @param string $siteId Символьный код сайта
     * @return string
     */
    public static function getLanguageIdBySiteId(string $siteId): string
    {
        return (string) self::getSites()[$siteId]['LANGUAGE_ID'];
    }

    /**
     * Получает идентификатор сайта по его языковой версии
     *
     * @param string $languageId
     * @return string
     */
    public static function getSiteIdByLanguageId(string $languageId): string
    {
        return self::getSites()->filter(function ($site) use ($languageId) {
            return $site['LANGUAGE_ID'] == $languageId;
        })->first()['LID'] ?? 'en';
    }

    /**
     * Получаем идентификатор сайта по его языку
     *
     * @return string
     */
    public static function getSiteIdByCurrentLanguage(): string
    {
        foreach (self::getSites() as $site) {
            if ($site['LANGUAGE_ID'] == LanguageHelper::getLanguageVersion()) {
                return $site['LID'];
            }
        }
        return SITE_ID;
    }
}

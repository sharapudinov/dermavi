<?php

namespace App\Core\System;

use App\Helpers\LanguageHelper;
use Illuminate\Support\Carbon;

/**
 * Данный класс реализует установку константы LANGUAGE_ID для мультиязычности.
 * @package App\Core\System
 */
class LanguageSetter
{
    /** @var array|array[] $countriesCodes - Многомерный массив языков и стран, привязанных к конкретному языку */
    private static $countriesCodes = [
        LanguageHelper::RUSSIAN_LANGUAGE => [
            'RU', 'BY', 'UA', 'AM', 'AZ', 'UZ', 'TJ', 'KG', 'KZ'
        ],
        LanguageHelper::CHINEESE_LANGUAGE => [
            'CN', 'HK'
        ],
        LanguageHelper::ENGLISH_LANGUAGE => [
            'AG', 'AI', 'AL', 'AN', 'AO', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AW', 'AX', 'BA', 'BB', 'BD',
            'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BL', 'BM', 'BN', 'BO', 'BQ', 'BR', 'BS', 'BT', 'BV',
            'BW', 'BZ', 'CA', 'CC', 'CD', 'CF', 'CG', 'CH', 'CI', 'CK', 'CL', 'CM', 'CO', 'CR', 'CS',
            'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DE', 'DJ', 'DK', 'DM', 'DO', 'DZ', 'EC', 'EE', 'EG',
            'ER', 'ES', 'ET', 'FI', 'FJ', 'FK', 'FM', 'FO', 'FR', 'GA', 'GB', 'GD', 'GE', 'GF', 'GG',
            'GH', 'GI', 'GL', 'GM', 'GN', 'GP', 'GQ', 'GR', 'GS', 'GT', 'GU', 'GW', 'GY', 'HN', 'HR',
            'HT', 'HU', 'ID', 'IE', 'IL', 'IM', 'IN', 'IQ', 'IR', 'IS', 'IT', 'JE', 'JM', 'JO', 'JP',
            'KE', 'KH', 'KI', 'KM', 'KN', 'KP', 'KR', 'KW', 'KY', 'LA', 'LB', 'LC', 'LI', 'LK', 'LR',
            'LS', 'LT', 'LU', 'LV', 'LY', 'MA', 'MC', 'MD', 'ME', 'MF', 'MG', 'MH', 'MK', 'ML', 'MM',
            'MN', 'MO', 'MP', 'MQ', 'MR', 'MS', 'MT', 'MU', 'MV', 'MW', 'MX', 'MY', 'MZ', 'NA', 'NC',
            'NE', 'NF', 'NG', 'NI', 'NL', 'NO', 'NP', 'NR', 'NU', 'NZ', 'OM', 'PA', 'PE', 'PF', 'PG',
            'PH', 'PK', 'PL', 'PM', 'PN', 'PR', 'PS', 'PT', 'PW', 'PY', 'QA', 'RE', 'RO', 'RS', 'RW',
            'SA', 'SB', 'SC', 'SD', 'SE', 'SG', 'SH', 'SI', 'SJ', 'SK', 'SL', 'SM', 'SN', 'SO', 'SR',
            'SS', 'ST', 'SV', 'SX', 'SY', 'SZ', 'TC', 'TD', 'TF', 'TG', 'TH', 'TK', 'TL', 'TM', 'TN',
            'TO', 'TR', 'TT', 'TV', 'TW', 'TZ', 'UG', 'UM', 'US', 'UY', 'VA', 'VC', 'VE', 'VG', 'VN',
            'VU', 'WF', 'WS', 'YE', 'YT', 'YU', 'ZA', 'ZM', 'ZW'
        ],
    ];

    /**
     * Список всех используемых на сайте языков отличных от языка по-умолчанию
     * Например ['en', 'by', 'kz', 'fr', 'cn']
     * @var array
     */
    private $langs = [
        LanguageHelper::RUSSIAN_LANGUAGE,
        LanguageHelper::ENGLISH_LANGUAGE,
        LanguageHelper::CHINEESE_LANGUAGE
    ];

    /** @var array|array[] $locales - Массив, описывающий символьные коды локалей для php и Carbon */
    private static $locales = [
        LanguageHelper::RUSSIAN_LANGUAGE => [
            'php' => 'ru_RU.utf8',
            'carbon' => 'ru'
        ],
        LanguageHelper::ENGLISH_LANGUAGE => [
            'php' => 'en_US.utf8',
            'carbon' => 'en'
        ],
        LanguageHelper::CHINEESE_LANGUAGE => [
            'php' => 'zh_CN.utf8',
            'carbon' => 'zh'
        ]
    ];

    /**
     * Задает локали
     *
     * @param string $languageId - Идентификатор языковой версии
     *
     * @return void
     */
    public static function setLocales(string $languageId): void
    {
        setlocale(LC_TIME, self::$locales[$languageId]['php']);
        Carbon::setLocale(self::$locales[$languageId]['carbon']);
    }

    /**
     * Просматриваем заголовки
     *
     * @return void
     */
    public function __invoke(): void
    {
        // сначала ищем в заголовке, через него работают аякс запросы
        if (isset($_SERVER['HTTP_X_LANGUAGE_ID']) && in_array($_SERVER['HTTP_X_LANGUAGE_ID'], $this->langs)) {
            $this->setLanguageId($_SERVER['HTTP_X_LANGUAGE_ID']);
            $this->setLocales($_SERVER['HTTP_X_LANGUAGE_ID']);
            return;
        }

        // если в заголовке нету, то во фрагменте урла, например https://mysite.ru/kz/news/
        if (!empty($_SERVER['REQUEST_URI'])) {
            foreach ($this->langs as $lang) {
                $search = "/$lang/";
                if (substr($_SERVER['REQUEST_URI'], 0, strlen($search)) === $search) {
                    $this->setLanguageId($lang);
                    self::setLocales($lang);
                    break;
                }
            }
        }
    }

    /**
     * Задаем язык системе
     *
     * @param string $languageId - Идентификатор языковой версии
     *
     * @return void
     */
    public function setLanguageId(string $languageId): void
    {
        define(LANGUAGE_ID, $languageId);
    }

    /**
     * Получаем доступные языки в системе
     *
     * @return array
     */
    public function getLangs(): array
    {
        return $this->langs;
    }

    /**
     * Возвращает массив, описывающий принадлежность кодов стран к языковым версиям сайта
     *
     * @return array|array[]
     */
    public static function getCountriesCodes(): array
    {
        return self::$countriesCodes;
    }
}

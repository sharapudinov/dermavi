<?php

namespace App\Helpers;

use App\Core\System\LanguageSetter;
use App\Models\HL\Country;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Models\ElementModel;
use Arrilot\BitrixModels\Models\EloquentModel;
use Arrilot\BitrixModels\Models\SectionModel;
use Arrilot\BitrixModels\Models\UserModel;
use Arrilot\BitrixModels\Queries\BaseQuery;

/**
 * Класс-хелпер для реализации мультиязычности
 * Class LanguageHelper
 * @package App\Helpers
 */
class LanguageHelper
{
    /** @var string - Идентификатор китайского языка */
    public const CHINEESE_LANGUAGE = 'cn';

    /** @var string */
    public const RUSSIAN_LANGUAGE = 'ru';

    /** @var string */
    public const ENGLISH_LANGUAGE = 'en';

    /** @var string - Версия сайта по-умолчанию */
    public const DEFAULT_LANGUAGE = self::RUSSIAN_LANGUAGE;

    /** @var string - Символьный код куки, содержащей языковую версию сайта */
    public const LANGUAGE_VERSION_COOKIE = 'language_version';

    /**
     * @var string|null
     */
    protected static $languageUserId;

    /**
     * Получаем идентификатор языковой версии сайта. Например 'ru', 'en', 'cn'
     *
     * @return string
     */
    public static function getLanguageVersion(): string
    {
        return (string)(static::$languageUserId ?? LANGUAGE_ID);
    }

    /**
     * Для случаев, когда язык текущего сайта не соответствует языку пользователя
     *
     * @param string $langId
     */
    public static function setLanguageUserId(string $langId): void
    {
        self::$languageUserId = $langId;
    }

    /**
     * Возвращает идентификаторы всех доступных языков.
     * @return array
     */
    public static function getAvailableLanguages(): array
    {
        return ['s1' => 'en', 's2' => 'ru', 's3' => 'cn'];
    }

    /**
     * @param string $langCode
     * @return bool
     */
    public static function isValidLanguage(string $langCode): bool
    {
        return in_array($langCode, static::getAvailableLanguages(), true);
    }

    /**
     * Значение lang атрибута для html тэга
     * В частности используется плагином Parsley
     * @see http://htmlbook.ru/html/attr/lang
     * @return string
     */
    public static function getHTMLLang(): string
    {
        switch (static::getLanguageVersion()) {
            case 'ru':
                return 'ru-RU';
                break;

            case 'cn':
                return 'zh-CN';
                break;

            case 'en':
            default:
                return 'en-US';
                break;
        }
    }

    /**
     * Активирована ли сейчас русская версия сайта
     *
     * @return bool
     */
    public static function isRussianVersion(): bool
    {
        return self::getLanguageVersion() === self::RUSSIAN_LANGUAGE;
    }

    /**
     * Активирована ли сейчас китайская версия сайта
     *
     * @return bool
     */
    public static function isChineseVersion(): bool
    {
        return self::getLanguageVersion() === self::CHINEESE_LANGUAGE;
    }

    /**
     * Проверяем включена ли версия по-умолчанию
     *
     * @return bool
     */
    public static function isDefaultVersion(): bool
    {
        return self::getLanguageVersion() === self::DEFAULT_LANGUAGE;
    }

    /**
     * Активирована ли сейчас английская версия сайта
     *
     * @return bool
     */
    public static function isEnglishVersion(): bool
    {
        return self::getLanguageVersion() === self::ENGLISH_LANGUAGE;
    }

    /**
     * Возвращает информацию для страны (символьный код и идентификатор языковой версии сайта для нее)
     *
     * @param Country $country - Модель страны
     *
     * @return array|string[]
     */
    public static function getCountryLanguageAndSiteId(Country $country): array
    {
        $languages = self::getAvailableLanguages();
        $siteId = null;
        $languageId = null;

        /** @var array|array[] $countriesCodesMapping - Массив, описывающий соотношение стран к языку */
        $countriesCodesMapping = LanguageSetter::getCountriesCodes();

        /** @var bool $isSNGCountry - Флаг, указывающий на принадлежность страны к СНГ */
        $isSNGCountry = in_array($country->getCountryCode(), $countriesCodesMapping[self::RUSSIAN_LANGUAGE]);
        if ($isSNGCountry) {
            $languageId = self::RUSSIAN_LANGUAGE;
        } else {
            /** @var bool $isChineseCountry - Флаг, указывающий на принадлежность страны к китайскому языку */
            $isChineseCountry = in_array($country->getCountryCode(), $countriesCodesMapping[self::CHINEESE_LANGUAGE]);
            if ($isChineseCountry) {
                $languageId = self::CHINEESE_LANGUAGE;
            } else {
                $languageId = self::ENGLISH_LANGUAGE;
            }
        }
        $siteId = array_flip($languages)[$languageId];

        return [
            'site_id' => $siteId,
            'language_id' => $languageId
        ];
    }

    /**
     * Получает значение мультиязычного свойства инфоблока для текущего языка или языка по-умолчанию
     *
     * @param ElementModel $element - элемент инфоблока
     * @param string       $code    - код мультиязычного поля без PROPERTY_ в начале
     * @param string       $lang    - язык, на котором нужно свойство
     *
     * @return mixed
     */
    public static function getIblockMultilingualPropertyValue(ElementModel $element, string $code, $lang = null)
    {
        $langId = null;

        if (null !== static::$languageUserId) {
            $langId = static::$languageUserId;
        }

        if (null !== $lang) {
            $langId = $lang;
        }

        return $element[implode(
            '_',
            ['PROPERTY', $code, $langId ? mb_strtoupper($langId) : mb_strtoupper(LANGUAGE_ID), 'VALUE']
        )]
            ?: $element[implode('_', ['PROPERTY', $code, mb_strtoupper(static::ENGLISH_LANGUAGE), 'VALUE'])];
    }

    /**
     * Получает значение мультиязычного свойства инфоблока для текущего языка
     *
     * @param ElementModel $element - элемент инфоблока
     * @param string       $code    - код мультиязычного поля без PROPERTY_ в начале
     * @param string       $lang    - язык, на котором нужно свойство
     *
     * @return mixed
     */
    public static function getIblockMultilingualPropertyValueWithoutDefault(
        ElementModel $element,
        string $code,
        $lang = null
    ) {
        return $element[implode(
            '_',
            ['PROPERTY', $code, $lang ? mb_strtoupper($lang) : mb_strtoupper(LANGUAGE_ID), 'VALUE']
        )];
    }

    /**
     * Возвращает привязанного к свойству ИБ пользователя на нужном языке, где отношение hasOne
     *
     * @param ElementModel $element    - Элемент инфоблока
     * @param string       $class      - Модель, на которую ссылается свойство (Передается $class:class)
     * @param string       $foreignKey - Код свойства, по которому происходит привязка (ID, XML_ID)
     * @param string       $localKey   - Код мультиязычного поля без PROPERTY_ в начале
     * @param string|null  $lang       - Язык, на котором нужно свойство
     *
     * @return BaseQuery
     */
    public static function getIblockRelationHasOneUserFieldValue(
        ElementModel $element,
        string $class,
        string $foreignKey,
        string $localKey,
        string $lang = null
    ): BaseQuery {
        return $element->hasOne(
            $class,
            $foreignKey,
            implode(
                '_',
                ['PROPERTY', $localKey, $lang ? mb_strtoupper($lang) : mb_strtoupper(LANGUAGE_ID), 'VALUE']
            )
        );
    }

    /**
     * Получает значение мультиязычного поля HL-блока для текущего языка или языка по умолчанию
     *
     * @param D7Model $element - элемент HL-блока
     * @param string  $code    - код мультиязычного поля без UF_ в начале
     * @param string  $lang    - язык, на котором нужно свойство
     *
     * @return mixed
     */
    public static function getHlMultilingualFieldValue(D7Model $element, string $code, $lang = null)
    {
        return $element[implode('_', ['UF', $code, $lang ? mb_strtoupper($lang) : mb_strtoupper(LANGUAGE_ID)])]
            ?: $element[implode('_', ['UF', $code, mb_strtoupper(static::ENGLISH_LANGUAGE)])];
    }

    /**
     * Получает значение мультиязычного поля пользователя для текущего языка или языка по умолчанию
     *
     * @param UserModel $element - элемент HL-блока
     * @param string    $code    - код мультиязычного поля без UF_ в начале
     * @param string    $lang    - язык, на котором нужно свойство
     *
     * @return mixed
     */
    public static function getUserMultilingualFieldValue(UserModel $element, string $code, $lang = null)
    {
        return $element[implode('_', ['UF', $code, $lang ? mb_strtoupper($lang) : mb_strtoupper(LANGUAGE_ID)])]
            ?: $element[implode('_', ['UF', $code, mb_strtoupper(static::ENGLISH_LANGUAGE)])];
    }

    /**
     * Возвращает значение мультиязычного поля обычной таблицы
     *
     * @param EloquentModel $element Модель
     * @param string        $code    Код свойства
     * @param string|null   $lang    Язык
     *
     * @return mixed
     */
    public static function getTableMultilingualFieldValue(EloquentModel $element, string $code, string $lang = null)
    {
        return $element[implode('_', [$code, $lang ? mb_strtolower($lang) : mb_strtolower(LANGUAGE_ID)])]
            ?: $element[implode('_', [$code, mb_strtolower(static::ENGLISH_LANGUAGE)])];
    }

    /***
     * Получает значение мультиязычного поля раздела ИБ для текущего языка или языка по умолчанию
     *
     * @param $section
     * @param $code
     *
     * @return mixed
     */
    public static function getSectionMultilingualFieldValue(SectionModel $section, string $code)
    {
        return $section[implode('_', ['UF', $code, mb_strtoupper(LANGUAGE_ID)])]
            ?: $section[implode('_', ['UF', $code, static::ENGLISH_LANGUAGE])];
    }

    /**
     * @param string $splitText
     * @param string $splitChar
     * @param string $splitLabel
     * @param array  $labels
     *
     * @return array
     */
    public static function splitTextByLang(
        string $splitText,
        string $splitChar = '|',
        string $splitLabel = ':',
        array $labels = ['en:', 'ru:', 'cn:']
    ): array {
        $resultText = [];
        $textList = explode($splitChar, $splitText);
        foreach ($textList as $text) {
            $hasLabel = false;
            foreach ($labels as $label) {
                if (strpos($text, $label) !== false) {
                    $hasLabel = true;
                    $label = trim(str_replace($splitLabel, '', $label));
                    $resultText[$label] = trim(str_replace($labels, '', $text));
                }
            }

            if (!$hasLabel) {
                $resultText['default'] = trim($text);
            }
        }

        return $resultText;
    }

    /**
     * @param string $text
     * @param string $label
     * @param string $splitChar
     * @param string $splitLabel
     * @param array  $labels
     *
     * @return string
     */
    public static function getMarkedTextByLang(
        string $text,
        string $label,
        string $splitChar = '|',
        string $splitLabel = ':',
        array $labels = ['en:', 'ru:', 'cn:']
    ): string {
        $resultText = static::splitTextByLang($text, $splitChar, $splitLabel, $labels);

        return (string)(!empty($resultText[$label]) ? $resultText[$label] : $resultText['default']);
    }

    /**
     * Возвращает значение поля для заданного языка.
     *
     * @param mixed       $model
     * @param string      $field
     * @param string      $suffix
     * @param string|null $lang
     *
     * @return string
     */
    public static function getMultilingualFieldValue(
        $model,
        string $field,
        string $suffix = '',
        string $lang = null
    ): string {
        $lang = $lang ?? LANGUAGE_ID;
        $nameParts = [$field, mb_strtoupper($lang)];

        if ($suffix !== '') {
            $nameParts[] = $suffix;
        }

        $result = (string)$model[implode('_', $nameParts)];
        if ($result === '' && $lang !== self::ENGLISH_LANGUAGE) {
            $nameParts[1] = mb_strtoupper(self::ENGLISH_LANGUAGE);
            $result = (string) $model[implode('_', $nameParts)];
        }

        return $result;
    }
}

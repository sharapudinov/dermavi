<?php

namespace App\Core\Currency;

use App\Core\Currency\Entity\CurrencyEntity;
use App\Core\Geolocation\Geolocation;
use App\Helpers\LanguageHelper;
use App\Helpers\TTL;
use App\Helpers\UrlHelper;
use App\Helpers\UserHelper;
use App\Models\Auxiliary\GeoZone;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use CCurrency;
use Illuminate\Support\Collection;

Loader::IncludeModule('currency');

/**
 * Класс для работы с валютой
 * Class Currency
 * @package App\Core
 */
class Currency extends CCurrency
{
    /** @var string - Валюта по умолчанию */
    const DEFAULT_CURRENCY = self::USD_CURRENCY;

    /** @var string - Доллар */
    const USD_CURRENCY = 'USD';

    /** @var string - Рубль */
    const RUB_CURRENCY = 'RUB';

    /** @var string - Ключ для куки */
    const COOKIE_NAME = 'currency';

    /** @var string - Ключ для кеширования */
    const CACHE_KEY = 'currency_user_';

    /** @var CurrencyEntity $currentCurrency - Текущая валюта */
    private static $currentCurrency;

    /**
     * Получаем установленную для пользователя валюту
     *
     * @return CurrencyEntity
     */
    public static function getCurrentCurrency(): CurrencyEntity
    {
        if (!static::$currentCurrency) {
            /** @var CurrencyEntity $currency - Валюта пользователя */
            $currency = null;

            //по новой логике, для авторизованного b2b и b2c одна валюта
            $collectionCurrency = (new self)->getCurrentUserCurrencyList();

            if (1 === $collectionCurrency->count()) {
                return $collectionCurrency->first();
            }

            if ($savedCurrency = $_COOKIE[Currency::COOKIE_NAME]) {
                $currency = (new self)->getCurrencyByAlphabetCode($savedCurrency);
            } else {
//                $countryAlphabetCode = Geolocation::getUserLocation()->getCountryCode();
//                /** @var GeoZone $country - Модель гео зоны */
//                $country  = GeoZone::where('country_code', $countryAlphabetCode)->first();
                $currency = (new self)->getCurrencyByNumberCode(
                    "RUB"/*$country ? $country->getCountryNumberCode() : null*/
                );
            }

            if (!$currency) {
                $currency = (new self)->getCurrencyByAlphabetCode(self::DEFAULT_CURRENCY);
            }

            static::$currentCurrency = $currency;
        }

        return static::$currentCurrency;
    }

    /**
     *  Список доступных валют, в зависимости от пользователя
     * @return Collection
     */
    public function getCurrentUserCurrencyList(): Collection
    {
        $currencyList = $this->getCurrenciesList();
        if (!UserHelper::isAuthorized()) {
            return $currencyList;
        }

        // для аукциона валюта только в usd
        if (UrlHelper::isHasPage('auctions')) {
            return (new Collection())->push($currencyList->offsetGet(self::USD_CURRENCY));
        }

        return UserHelper::isLegalEntity()
            ? (new Collection())->push($currencyList->offsetGet(self::USD_CURRENCY))
            : (new Collection())->push($currencyList->offsetGet(self::RUB_CURRENCY));
    }

    /**
     * Получаем список доступных валют
     *
     * @return Collection|CurrencyEntity[]
     */
    public function getCurrenciesList(): Collection
    {
        return cache(
            get_default_cache_key(self::class) . '_getCurrenciesList',
            TTL::DAY,
            function () {
                $list           = new Collection();
                $orderBy        = 'sort';
                $orderDirection = 'asc';
                $currencyQuery  = parent::GetList($orderBy, $orderDirection, LanguageHelper::getLanguageVersion());

                while ($currency = $currencyQuery->GetNext()) {
                    $entity = new CurrencyEntity($currency);
                    /** @var CurrencyEntity $entity */
                    $list->put($entity->getSymCode(), $entity);
                }

                return $list;
            }
        );
    }

    /**
     * Получает список кодов валют
     *
     * @return array
     */
    public function getCurrenciesCodeList(): array
    {
        $codes = [];
        $list  = $this->getCurrenciesList();
        foreach ($list as $currency) {
            $codes[] = $currency->getSymCode();
        }

        return $codes;
    }

    /**
     * Возвращает код в зависимости от пользователя
     *
     * @param string $code
     *
     * @return string
     */
    public static function replaceCodeByUserType(string $code): string
    {
        if (!UserHelper::isAuthorized()) {
            return $code;
        }

        return UserHelper::isLegalEntity() ? self::USD_CURRENCY : self::RUB_CURRENCY;
    }

    /**
     * Получаем информацию о валюте по умолчанию
     *
     * @return CurrencyEntity
     */
    public function getDefaultCurrency(): CurrencyEntity
    {
        return $this->getCurrencyByAlphabetCode(self::DEFAULT_CURRENCY);
    }

    /**
     * Получаем необходимую валюту по ее буквенному коду
     *
     * @param string $code - Символьный код валюты
     *
     * @return CurrencyEntity|null
     */
    public function getCurrencyByAlphabetCode(string $code): ?CurrencyEntity
    {
        static $cache;
        if (!isset($cache[$code])) {
            $cache[$code] = $this->getCurrenciesList()->filter(
                function (CurrencyEntity $currency) use ($code) {
                    return $currency->getSymCode() === $code;
                }
            )->first();
            $cache[$code] = $cache[$code] ?? false;
        }

        return $cache[$code] ?: null;
    }

    /**
     * Получаем необходимую валюту по ее цифровому коду
     *
     * @param string $num - Цифровой код валюты
     *
     * @return CurrencyEntity|null
     */
    private function getCurrencyByNumberCode(string $num): ?CurrencyEntity
    {
        $currenciesList = $this->getCurrenciesList();

        return $currenciesList->filter(
            function (CurrencyEntity $currency) use ($num) {

                return $currency->getNumberCode() == $num;
            }
        )->first();
    }

    /**
     * Обновляем валюту
     *
     * @param string $symCode - Трехзначный символьный код валюты (Например, USD)
     * @param float  $amount  - Курс к рублю
     *
     * @return bool
     */
    public static function updateCurrency(string $symCode, float $amount): bool
    {
        return parent::Update(
                $symCode,
                [
                    'CURRENCY' => $symCode,
                    'AMOUNT'   => $amount,
                ]
            ) !== false;
    }

    /**
     * @param string $currencySymCode
     * @return string
     */
    public static function getCurrencySymbol(string $currencySymCode): string
    {
        $currency = static::getCurrentCurrency();

        if ($currencySymCode !== $currency->getSymCode()) {
            $currency = (new static())->getCurrencyByAlphabetCode($currencySymCode);
        }

        return $currency ? $currency->getSymbol() : '';
    }
}

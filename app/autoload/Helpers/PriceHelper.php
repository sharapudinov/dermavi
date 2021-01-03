<?php

namespace App\Helpers;

use App\Core\Currency\Currency;
use App\Core\Currency\Entity\CurrencyEntity;
use App\Core\User\Context\UserContext;
use App\Models\Catalog\Diamond;
use Bitrix\Main\ObjectNotFoundException;

/**
 * Класс-хелпер для работы с ценами
 * Class PriceHelper
 * @package App\Helpers
 */
class PriceHelper
{
    /**
     * Конвертируем цену в определенную валюту
     *
     * @param float          $price    - Цена
     * @param CurrencyEntity $currency - Валюта
     *
     * @return float
     */
    public static function getPriceInSpecificCurrency(float $price, CurrencyEntity $currency): float
    {
        return ceil($price / $currency->getAmount());
    }

    /**
     * Возвращает цену, сконвертированную из текущей валюты в рубли (для корзины)
     *
     * @param float $price - Цена
     *
     * @return float
     */
    public static function getPreparedForCartPrice(float $price): float
    {
        if (Currency::getCurrentCurrency()->getSymCode() !== 'RUB') {
            $price = $price * Currency::getCurrentCurrency()->getAmount();
        }

        return $price;
    }

    /**
     * Получаем сумму, сконвертированную из указанной в $currency валюте, в текущую валюту
     *
     * @param float       $price    - Цена
     * @param null|string $currency - Валюта этой цены
     *
     * @return float возвращает сумму в текущей валюте
     */
    public static function getPriceInCurrentCurrency(
        float $price = null,
        string $currency = null,
        string $toCurrency = null
    ): float {
        $converted       = $price;
        $currentCurrency = $toCurrency
            ? (new Currency)->getCurrencyByAlphabetCode($toCurrency)
            : Currency::getCurrentCurrency();

        $defaultCurrency = $currency
            ? (new Currency)->getCurrencyByAlphabetCode($currency)
            : (new Currency)->getDefaultCurrency();
        if ($currentCurrency->getSymCode() == Currency::RUB_CURRENCY) {
            if ($currentCurrency->getSymCode() != $defaultCurrency->getSymCode()) {
                $converted = $price * $defaultCurrency->getAmount();
            }
        } else {
            $roublesPrice = $price * $defaultCurrency->getAmount();
            $converted    = $roublesPrice / $currentCurrency->getAmount();
        }

        return (float)$converted;
    }

    /**
     * Получаем цену в валюте по умолчанию
     *
     * @param float $price
     *
     * @return float
     */
    public static function getPriceInDefaultCurrency(float $price): float
    {
        $converted       = $price;
        $defaultCurrency = (new Currency)->getDefaultCurrency();
        $currentCurrency = Currency::getCurrentCurrency();
        if ($defaultCurrency->getSymCode() != $currentCurrency->getSymCode()) {
            $converted = ($price * $currentCurrency->getAmount()) / $defaultCurrency->getAmount();
        }

        return $converted;
    }

    /**
     * Конвертирует рубли в валюту по умолчанию
     *
     * @param float $price - Цена в рублях
     *
     * @return float
     */
    public static function convertRoublesInDefaultCurrent(float $price): float
    {
        $defaultCurrency = (new Currency)->getDefaultCurrency();

        return $price / $defaultCurrency->getAmount();
    }

    /**
     * Возвращает цену бриллианта в зависимости от типа лица (Физ лицо, юр лицо)
     *
     * @param Diamond $diamond - Бриллиант
     *
     * @return float
     * @see http://redmine.greensight.ru/issues/51427
     */
    public static function getPriceBecauseOfPersonType(Diamond $diamond): float
    {
        $price = $diamond['PROPERTY_PRICE_VALUE'];
        if (static::isTaxesConsidered()) {
            $price = static::calculateWithTax((float)$price);
        }

        return (float)$price;
    }

    /**
     * Возвращает цену за карат в зависимости от типа лица (Физ лицо, юр лицо)
     *
     * @param Diamond $diamond - Бриллиант
     *
     * @return float
     * @see http://redmine.greensight.ru/issues/51427
     */
    public static function getCaratPriceBecauseOfPersonType(Diamond $diamond): float
    {
        $price = $diamond['PROPERTY_PRICE_CARAT_VALUE'];
        if (static::isTaxesConsidered()) {
            $price = static::calculateWithTax((float)$price);
        }

        return (float)$price;
    }

    /**
     * Считает цену с НДС
     *
     * @param float $price - Цена без НДС
     *
     * @return float
     */
    public static function calculateWithTax(float $price): float
    {
        return $price * (1 + 20 / 100);
    }

    /**
     * Вычитает из цены НДС
     *
     * @param float $price
     * @return float
     */
    public static function calculateWithoutTax(float $price): float
    {
        return (float)($price / (1 + 20 / 100));
    }

    /**
     * Нужно ли в текущем контексте учитывать налоги
     *
     * @return bool
     */
    public static function isTaxesConsidered(): bool
    {
        return static::isTaxesConsideredByContext(UserContext::getCurrent());
    }

    /**
     * @param UserContext $context
     * @return bool
     */
    public static function isTaxesConsideredByContext(UserContext $context): bool
    {
        return !$context->isLegalEntity();
    }

    /**
     * Конвертация валюты цены
     *
     * @param float $price - Цена
     * @param null|string $from - Валюта цены
     * @param string|null $to - Валюта, в которую нужно перевести цену
     * @return float
     * @throws ObjectNotFoundException
     */
    public static function convertPriceCurrency(float $price = null, string $from = null, string $to = null): float
    {
        $currencyInstance = new Currency();

        if ($from === null) {
            $fromCurrency = $currencyInstance->getDefaultCurrency();
            $from = $fromCurrency->getSymCode();
        } else {
            $fromCurrency = $currencyInstance->getCurrencyByAlphabetCode($from);
            if (!$fromCurrency) {
                throw new ObjectNotFoundException('Default currency not found');
            }
        }

        $to = $to ?? UserContext::getCurrent()->getCurrency();
        $toCurrency = $currencyInstance->getCurrencyByAlphabetCode($to);
        if (!$toCurrency) {
            throw new ObjectNotFoundException('Current currency not found');
        }

        if ($from === $to) {
            return $price;
        }

        // Исходим из того, что getAmount всегда возращает курс по отношению к рублю
        if ($toCurrency->getSymCode() === Currency::RUB_CURRENCY) {
            $converted = $price * $fromCurrency->getAmount();
        } else {
            $roublePrice = $price * $fromCurrency->getAmount();
            $converted = $roublePrice / $toCurrency->getAmount();
        }

        return (float)$converted;
    }
}

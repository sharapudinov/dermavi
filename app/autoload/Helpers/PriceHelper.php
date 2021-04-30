<?php

namespace App\Helpers;

use App\Core\Currency\Currency;
use App\Core\Currency\Entity\CurrencyEntity;
use App\Core\User\Context\UserContext;
use App\Models\Catalog\Catalog;
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
    /***Как посчитать стоимость товара или предложения со всеми скидками***/
    public static function getFinalPriceInCurrency($item_id, $cnt = 1, $getName="N", $sale_currency = 'RUB') {
        \CModule::IncludeModule("iblock");
        \CModule::IncludeModule("catalog");
        \CModule::IncludeModule("sale");
        global $USER;

        // Проверяем, имеет ли товар торговые предложения?
        if(\CCatalogSku::IsExistOffers($item_id)) {

            // Пытаемся найти цену среди торговых предложений
            $res = \CIBlockElement::GetByID($item_id);

            if($ar_res = $res->GetNext()) {
                $productName = $ar_res["NAME"];
                if(isset($ar_res['IBLOCK_ID']) && $ar_res['IBLOCK_ID']) {

                    // Ищем все тогровые предложения
                    $offers = \CIBlockPriceTools::GetOffersArray(array(
                                                                    'IBLOCK_ID' => $ar_res['IBLOCK_ID'],
                                                                    'HIDE_NOT_AVAILABLE' => 'Y',
                                                                    'CHECK_PERMISSIONS' => 'Y'
                                                                ), array($item_id), null, null, null, null, null, null, array('CURRENCY_ID' => $sale_currency), $USER->getId(), null);

                    foreach($offers as $offer) {

                        $price = \CCatalogProduct::GetOptimalPrice($offer['ID'], $cnt, $USER->GetUserGroupArray(), 'N');
                        if(isset($price['PRICE'])) {

                            $final_price = $price['PRICE']['PRICE'];
                            $currency_code = $price['PRICE']['CURRENCY'];

                            // Ищем скидки и высчитываем стоимость с учетом найденных
                            $arDiscounts = \CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N");
                            if(is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
                                $final_price = \CCatalogProduct::CountPriceWithDiscount($final_price, $currency_code, $arDiscounts);
                            }

                            // Конец цикла, используем найденные значения
                            break;
                        }

                    }
                }
            }

        } else {

            // Простой товар, без торговых предложений (для количества равному $cnt)
            $price = \CCatalogProduct::GetOptimalPrice($item_id, $cnt, $USER->GetUserGroupArray(), 'N');

            // Получили цену?
            if(!$price || !isset($price['PRICE'])) {
                return false;
            }

            // Меняем код валюты, если нашли
            if(isset($price['CURRENCY'])) {
                $currency_code = $price['CURRENCY'];
            }
            if(isset($price['PRICE']['CURRENCY'])) {
                $currency_code = $price['PRICE']['CURRENCY'];
            }

            // Получаем итоговую цену
            $final_price = $price['PRICE']['PRICE'];

            // Ищем скидки и пересчитываем цену товара с их учетом
            $arDiscounts = \CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N", 2);
            if(is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
                $final_price = \CCatalogProduct::CountPriceWithDiscount($final_price, $currency_code, $arDiscounts);
            }

            if($getName=="Y"){
                $res = \CIBlockElement::GetByID($item_id);
                $ar_res = $res->GetNext();
                $productName = $ar_res["NAME"];
            }

        }

        // Если необходимо, конвертируем в нужную валюту
        if($currency_code != $sale_currency) {
            $final_price = \CCurrencyRates::ConvertCurrency($final_price, $currency_code, $sale_currency);
        }

        $arRes = array(
            "PRICE"=>$price['PRICE']['PRICE'],
            "FINAL_PRICE"=>$final_price,
            "CURRENCY"=>$sale_currency,
            "DISCOUNT"=>$arDiscounts,
        );

        if($productName!="")
            $arRes['NAME']= $productName;

        return $arRes;

    }
}

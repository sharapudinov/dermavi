<?php

namespace App\Core\Sale\Entity\CartType;

use App\Core\Currency\Currency;
use App\Core\Currency\Entity\CurrencyEntity;

/**
 * Класс, описывающий объект типа корзины аукционных товаров
 * Class AuctionsCartType
 *
 * @package App\Core\Sale\Entity\CartType
 */
class AuctionsCartType implements CartTypeInterface
{
    /**
     * Возвращает объект, описывающий валюту
     *
     * @return CurrencyEntity
     */
    public function getCurrency(): CurrencyEntity
    {
        return (new Currency())->getCurrencyByAlphabetCode(Currency::USD_CURRENCY);
    }

    /**
     * Возвращает идентификатор сайта
     *
     * @return string
     */
    public function getSiteId(): string
    {
        //это фиаско.. на это завязан тип товара, прикрепляемы к письму
        //app/autoload/Helpers/OrderHelper.php L247
        return 's2';
    }

    /**
     * Возвращает имя компонента для письма
     *
     * @return string
     */
    public function getMailComponentName(): string
    {
        return 'email.dispatch:order.new.user.auctions';
    }
}

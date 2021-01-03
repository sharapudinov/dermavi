<?php

namespace App\Core\Sale\Entity\CartType;

use App\Core\Currency\Currency;
use App\Core\Currency\Entity\CurrencyEntity;

/**
 * Класс, описывающий объект типа корзины конструктора ЮБИ
 * Class ConstructorCartType
 *
 * @package App\Core\Sale\Entity\CartType
 */
class ConstructorCartType implements CartTypeInterface
{
    /**
     * Возвращает объект, описывающий валюту
     *
     * @return CurrencyEntity
     */
    public function getCurrency(): CurrencyEntity
    {
        return (new Currency())->getCurrencyByAlphabetCode(Currency::RUB_CURRENCY);
    }

    /**
     * Возвращает идентификатор сайта
     *
     * @return string
     */
    public function getSiteId(): string
    {
        return 's3';
    }

    /**
     * Возвращает имя компонента для письма
     *
     * @return string
     */
    public function getMailComponentName(): string
    {
        return 'email.dispatch:order.user.index';
    }
}

<?php

namespace App\Core\Sale\Entity\CartType;

use App\Core\Currency\Currency;
use App\Core\Currency\Entity\CurrencyEntity;

/**
 * Класс, описывающий объект типа корзины по-умолчанию
 * Class DefaultCartType
 *
 * @package App\Core\Sale\Entity\CartType
 */
class DefaultCartType implements CartTypeInterface
{
    /**
     * Возвращает объект, описывающий валюту
     *
     * @return CurrencyEntity
     */
    public function getCurrency(): CurrencyEntity
    {
        return Currency::getCurrentCurrency();
    }

    /**
     * Возвращает идентификатор сайта
     *
     * @return string
     */
    public function getSiteId(): string
    {
        //это фиаско.. на это завязан тип товара, прикрепляемый к письму
        //app/autoload/Helpers/OrderHelper.php L247
        return 's1';
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

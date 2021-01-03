<?php

namespace App\Core\Sale\Entity\CartType;

use App\Core\Currency\Entity\CurrencyEntity;

/**
 * Интерфейс, описывающий методы объектов типов корзины
 * Interface CartTypeInterface
 *
 * @package App\Core\Sale\Entity\CartType
 */
interface CartTypeInterface
{
    /**
     * Возвращает объект, описывающий валюту
     *
     * @return CurrencyEntity
     */
    public function getCurrency(): CurrencyEntity;

    /**
     * Возвращает идентификатор сайта
     *
     * @return string
     */
    public function getSiteId(): string;

    /**
     * Возвращает имя компонента для письма
     *
     * @return string
     */
    public function getMailComponentName(): string;
}

<?php

namespace App\Core\Sale;

use App\Helpers\UserCartHelper;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Sale\BasketBase;

class PaySystemConstructorRestriction
{
    /**
     * Определяет доступен ли данный способ оплаты в зависимости от наличия в корзине товаров из конструктора.
     *
     * @param $paymentCode - код способа оплаты
     * @param BasketBase $basket
     *
     * @return bool
     * @throws ArgumentNullException
     */
    public static function check($paymentCode, BasketBase $basket): bool
    {
        // отключаем способ оплаты "в офисе" если в корзине есть хотя бы один товар из конструктора
        return $paymentCode !== 'OFFICE_PAYMENT' || !UserCartHelper::hasConstructorProductInBasket($basket);
    }
}

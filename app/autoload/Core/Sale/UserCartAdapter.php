<?php

namespace App\Core\Sale;

use App\Core\Sale\Entity\CartType\CartTypeInterface;

/**
 * Класс, описывающий логику получения специальной корзины. Реализует шаблон "Адаптер".
 * Class UserCartAdapter
 * @deprecated Наследуемый UserCart является синглтоном! Используй либо UserCart, либо реализуй действительно Адаптер.
 *
 * @package App\Core\Sale
 */
class UserCartAdapter extends UserCart
{
    /**
     * UserCartSpecial constructor.
     *
     * @param CartTypeInterface $cartType Тип корзины
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public function __construct(CartTypeInterface $cartType)
    {
        parent::__construct($cartType);
    }

    /**
     * Возвращает стоимость заказа
     *
     * @return float
     */
    public function getCartPrice(): float
    {
        return $this->getCart()->getPrice();
    }
}

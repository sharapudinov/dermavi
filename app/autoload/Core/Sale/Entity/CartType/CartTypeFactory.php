<?php

namespace App\Core\Sale\Entity\CartType;

/**
 * Класс, реализующий шаблон Simple Factory, который описывает логику получения необходимого класса типа корзины
 * Class CartTypeFactory
 *
 * @package App\Core\Sale\Entity\CartType
 */
class CartTypeFactory
{
    /**
     * Возвращает необходимый тип корзины
     *
     * @param string|null $type Символьный код типа корзины
     *
     * @return CartTypeInterface
     */
    public static function getCartType(string $type = null): CartTypeInterface
    {
        if (!$type) {
            $type = 'default';
        }

        switch ($type) {
            case 'auction':
                return new AuctionsCartType();
                break;
            case 'constructor':
                return new ConstructorCartType();
                break;
            default:
                return new DefaultCartType();
                break;
        }
    }
}

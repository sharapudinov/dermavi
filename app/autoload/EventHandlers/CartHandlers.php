<?php

namespace App\EventHandlers;

use App\Helpers\UserCartHelper;
use Arrilot\BitrixCacher\Cache;

/**
 * Класс обработчик для работы с корзиной
 * Class CartHandlers
 * @package App\EventHandlers
 */
class CartHandlers
{
    /**
     * Сбрасываем кеш корзины пользователя при каких-либо действиях с ней
     *
     * @param int $itemId
     * @param array|null $arFields
     */
    public static function flushCartCache(): void
    {
        Cache::flush(self::getCacheInitDir());
    }

    /**
     * Уникальный init dir кэша для корзины пользователя.
     * @return string
     */
    public static function getCacheInitDir()
    {
        if (user()) {
            return UserCartHelper::CART_CACHE_KEY . 'au_' . user()->getId();
        } else {
            return UserCartHelper::CART_CACHE_KEY . 'bu_' . \CSaleBasket::GetBasketUserID();
        }
    }
}

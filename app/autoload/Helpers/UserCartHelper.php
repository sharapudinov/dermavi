<?php

namespace App\Helpers;

use App\Core\Currency\Currency;
use App\Core\Sale\Entity\CartType\CartTypeInterface;
use App\Core\Sale\Entity\CartType\DefaultCartType;
use App\Core\Sale\UserCart;
use App\Core\Sale\View\OrderItemViewModel;
use App\EventHandlers\CartHandlers;
use App\Models\Auxiliary\Sale\BitrixBasketItem;
use App\Models\User;
use Arrilot\BitrixCacher\Cache;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use CSaleUser;
use Illuminate\Support\Collection;

/**
 * Класс-хелпер для работы с корзиной
 * Class UserCartHelper
 * @package App\Helpers
 */
class UserCartHelper
{
    /** @var string - Ключ для кеширования корзины авторизованного пользователя */
    const CART_CACHE_KEY = 'product_cart_user_';

    /**
     * Получаем количество позиций в корзине
     *
     * @param Basket|null $cart - Корзина
     * @return int
     */
    public static function getCartPositionsCount(Basket $cart = null): int
    {
        if ($cart) {
            $count = count($cart->getBasketItems());
        } else {
            $count = count(UserCart::getUserCart(new DefaultCartType())->getBasketItems());
        }

        return $count;
    }

    /**
     * Проверяет может ли заданный/текущий пользователь оформить заказ.
     * @param User|null $user
     * @param Basket|null $cart
     * @return bool
     */
    public static function canCheckout(User $user = null, Basket $cart = null): bool
    {
        $user = $user ?? user();
        if (!$user || !$user->isAuthorized()) {
            return false;
        }

        if (static::getCartPositionsCount($cart) === 0) {
            return false;
        }

        return true;
    }

    /**
     * Проверяет может ли пользователь совершить покупку
     *
     * @param float $price Стоимость корзины
     *
     * @return bool
     */
    public static function canPay(float $price = null): bool
    {
        return true;
    }

    /**
     * Возвращает информацию о корзине пользователя
     *
     * @param CartTypeInterface $cartType Тип корзины
     * @param UserCart $userCart Адаптер корзины пользователя
     *
     * @return array|mixed[]
     * @throws \Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix
     * @throws ArgumentNullException
     */
    public static function loadCartInfo(CartTypeInterface $cartType, UserCart $userCart = null): array
    {
        /** @var float $cartPrice - Общая цена на корзину */
        $cartPrice = 0.0;

        /** @var int $cartItemPrice - Общая цена на товары */
        $cartItemPrice = 0;

        /** @var int $cartServicePrice - Общая цена на платные услуги */
        $cartServicePrice = 0;

        /** @var int $cartServiceDeliveryDays - Кол-во дней на доставку */
        $cartServiceDeliveryDays = 0;


        if (!$userCart) {
            $userCart = UserCart::getInstance($cartType);
        }

        $items = $userCart->getCart()->getBasketItems();
        $cartItemsIds = self::getCartItemsIds($items);

        /** @var Collection|BitrixBasketItem[] $basketItems - Коллекция товаров в корзине */
        $basketItems = self::getBitrixBasketItems($cartItemsIds);
        $cartItemPrice = $basketItems->sum(
            function ($item) {
                return $item->getPrice()*$item->getQuantity();
            }
        );

        $cartPrice = $cartItemPrice + $cartServicePrice;

        return [
            'currency'                   => Currency::getCurrentCurrency(),
            'cart_cost'                  => $cartPrice,
            'cart_item_cost'             => $cartItemPrice,
            'cart_service_cost'          => $cartServicePrice,
            'basket_items'               => $basketItems,
            'float_cart_price'           => $cartPrice,
        ];
    }

    /**
     * Переназначает корзину одного пользователя текущему
     *
     * @param null|int $oldFUserId FUserId старого пользователя
     *
     * @return void
     */
    public static function transferCart(?int $oldFUserId): void
    {
        if (null === $oldFUserId) {
            return;
        }

        Loader::IncludeModule('sale');
        $cartItems = db()->query('SELECT ID FROM b_sale_basket WHERE FUSER_ID = ' . $oldFUserId);
        while ($item = $cartItems->fetch()) {
            db()->query(
                'UPDATE b_sale_basket SET FUSER_ID = ' . CSaleUser::getFUserCode() . ' WHERE ID = ' . $item['ID']
            );
        }

        Cache::flush(CartHandlers::getCacheInitDir());
    }

    /**
     * Возвращает элементы корзины по их id
     *
     * @param array $cartItemsIds
     *
     * @return Collection
     */
    public static function getBitrixBasketItems(array $cartItemsIds): Collection
    {
        return BitrixBasketItem::filter(['ID' => $cartItemsIds])->getList();
    }

    /**
     * Возвращает массив идентификаторов товаров в корзине
     *
     * @param array|BasketItem[] $items
     *
     * @return array
     */
    public static function getCartItemsIds(array $items): array
    {
        $cartItemsIds = [];

        /** @var BasketItem $item */
        foreach ($items as $item) {
            $cartItemsIds[] = $item->getId();
        }

        return $cartItemsIds;
    }
}

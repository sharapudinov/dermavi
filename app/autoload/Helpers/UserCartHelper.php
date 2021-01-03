<?php

namespace App\Helpers;

use App\Core\Auctions\Entity\Cart\Auction;
use App\Core\Auctions\Entity\Cart\AuctionLot;
use App\Core\Auctions\Entity\Cart\AuctionPB;
use App\Core\Auctions\Entity\Cart\AuctionPBLot;
use App\Core\Currency\Currency;
use App\Core\Sale\Entity\CartType\CartTypeInterface;
use App\Core\Sale\Entity\CartType\DefaultCartType;
use App\Core\Sale\UserCart;
use App\Core\Sale\View\DiamondPaidServiceCollection;
use App\Core\Sale\View\OrderItemViewModel;
use App\EventHandlers\CartHandlers;
use App\Models\Auctions\LotBid;
use App\Models\Auctions\PBLotBid;
use App\Models\Auxiliary\Sale\BitrixBasketItem;
use App\Models\Catalog\PaidService;
use App\Models\Catalog\ProductInterface;
use App\Models\Jewelry\JewelryConstructorReadyProduct;
use App\Models\User;
use Arrilot\BitrixCacher\Cache;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketBase;
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
     * @var array|float
     * Массив цен на сертификат GIA в зависимости от каратности ['вес_карат_от-вес_карат_до' => 'цена_в_долларах_сша']
     * @deprecated Временная переменная. Потом, скорее всего, надо будет делать через бд
     */
    private static $giaCertificatePrices = [
        '0.01-0.29' => 162.0,
        '0.30-0.99' => 216.0,
        '1-1.99' => 270.0,
        '2-2.99' => 324.0,
        '3-3.99' => 445.0,
        '4-4.99' => 540.0,
        '5-5.99' => 648.0,
    ];
    /**
     * @var array|float
     * Массив цен на сертификат GIA в зависимости от каратности ['вес_карат_от-вес_карат_до' => 'цена_в_долларах_рублях']
     * @deprecated Временная переменная. Потом, скорее всего, надо будет делать через бд
     */
    private static $giaCertificatePricesRub = [
        '0.01-0.29' => 12000,
        '0.30-0.99' => 16000,
        '1-1.99' => 20000,
        '2-2.99' => 24000,
        '3-3.99' => 33000,
        '4-4.99' => 40000,
        '5-5.99' => 48000,
    ];


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
    public static function canPay(float $price): bool
    {
        $user = user();

        if ($user) {
            if ($user->isLegalEntity() && $user->isPurchaseAvailableOver100()) {
                return true;
            } elseif (!$user->isLegalEntity()) {
                if (PriceHelper::getPreparedForCartPrice($price) < 100000 ) {
                    return true;
                } elseif (PriceHelper::getPreparedForCartPrice($price) >= 100000 && $user->isPurchaseAvailableOver100()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Привязывает платные услуги к позициям в корзине
     *
     * @param Collection|BitrixBasketItem[] $basketItems - Коллекция позиций в корзине
     * @param Collection $cartItems - Пустая коллекция для занесения в нее объектов, описывающих товары в корзине
     * @return void
     */
    public static function attachServiceToProduct(Collection &$basketItems, Collection &$cartItems): void
    {
        /** @var Collection|OrderItemViewModel[] $services - Коллекция объектов, описывающих услугу товара */
        $services = new Collection;

        /** @var BitrixBasketItem $basket */
        foreach ($basketItems as $basketItem) {
            if ($basketItem->getProduct()) {
                $cartItems[$basketItem->getId()] = new OrderItemViewModel($basketItem);
            } elseif ($basketItem->service) {
                $services[$basketItem->getId()] = new OrderItemViewModel($basketItem);
            }
        }


        $services = $services->groupBy(function (OrderItemViewModel $viewModel) {
            return $viewModel->getOwnerId();
        });

        foreach ($cartItems as $item) {
            /** @var OrderItemViewModel[] $itemServices */
            $itemServices = $services[$item->getBasketId()];
            if (!$itemServices) {
                continue;
            }

            foreach ($itemServices as $itemService) {
                $item->attachService($itemService);
            }
        }
    }

    /**
     * Получает цену сертификата GIA на бриллиант в зависимости от его веса
     *
     * @param ProductInterface $product - Продукт
     *
     * @return int|null
     * @deprecated Временный метод. Потом, скорее всего, надо будет делать через бд
     */
    public static function getGiaCertificatePriceForDiamond(ProductInterface $product, $currentCurrency = false): ?int
    {
        $arrayUsd = array_filter(self::$giaCertificatePrices, function ($key) use ($product) {
            $range = explode('-', $key);
            if ($product->getWeight() >= $range[0] && $product->getWeight() <= $range[1]) {
                return $key;
            }
        }, ARRAY_FILTER_USE_KEY);
        $priceUsd = end($arrayUsd);

        $arrayRub = array_filter(self::$giaCertificatePricesRub, function ($key) use ($product) {
            $range = explode('-', $key);
            if ($product->getWeight() >= $range[0] && $product->getWeight() <= $range[1]) {
                return $key;
            }
        }, ARRAY_FILTER_USE_KEY);
        $priceRub = end($arrayRub);

        $priceRub = end(array_filter(self::$giaCertificatePricesRub, function ($key) use ($product) {
            $range = explode('-', $key);
            if ($product->getWeight() >= $range[0] && $product->getWeight() <= $range[1]) {
                return $key;
            }
        }, ARRAY_FILTER_USE_KEY));

        $finalPrice = null;
        $currency = new Currency;
        if ($currency::getCurrentCurrency()->getSymCode() === 'RUB') {
            return $priceRub;
        }
        if ($priceUsd) {
            $finalPrice = $priceUsd
                * (new Currency)->getCurrencyByAlphabetCode(Currency::USD_CURRENCY)->getAmount();
            if($currentCurrency) {
                $finalPrice = $finalPrice / $currency->getCurrentCurrency()->getAmount();
            }
        }

        return $finalPrice;
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

        /** @var int $cartServiceDeliveryDays - Кол-во дней на доставку*/
        $cartServiceDeliveryDays = 0;

        /** @var DiamondPaidServiceCollection|null $services - Класс для работы с платными услугами */
        $services = null;

        if (!$userCart) {
            $userCart = UserCart::getInstance($cartType);
        }

        // Удаляем из корзины товары, которые недоступны для покупки
        // И подсчитываем сумму корзины
        $items = $userCart->getBasketItems();
        $refreshBasketInfo = false;
        $currency = Currency::getCurrentCurrency();

        /** @var BitrixBasketItem $item */
        foreach ($items as $item) {
            $tmpProduct = $item->getProduct();
            if ($tmpProduct && !$tmpProduct->isAvailableForSelling()) {
                UserCart::removeFromCart($item->getProductId());
                $refreshBasketInfo = true;
                continue;
            }
            //Для более точного расчёта цен
            $cartPrice += PriceHelper::getPriceInSpecificCurrency(
                $tmpProduct->getPriceForCart(),
                $currency
            );
        }
        if ($refreshBasketInfo) {
            $userCart->refreshBasketInfo();
        }

        $items = $userCart->getCart()->getBasketItems();

        $cartItemsIds = self::getCartItemsIds($items);

        /** @var Collection|OrderItemViewModel[] $cartItems - Коллекция объектов, описывающих товар в корзине */
        $cartItems = new Collection;

        /** @var Collection|BitrixBasketItem[] $basketItems - Коллекция товаров в корзине */
        $basketItems = self::getBitrixBasketItems($cartItemsIds);

        self::attachServiceToProduct($basketItems, $cartItems);

        if ($cartItems->isNotEmpty()) {
            //Тут получалась округлённая цена
            //$cartPrice = $userCart->getCartPrice();
            foreach ($cartItems as $item) {
                $cartItemPrice += $item->getPrice();
                $cartServicePrice += $item->getAttachedServicePrice();

                $tmpDeliveryDays = (int)$item->getAttachedServiceDeliveryDays(PaidService::DELIVERY_DAYS_PROP_CODE);

                if($tmpDeliveryDays > $cartServiceDeliveryDays) {
                    $cartServiceDeliveryDays = $tmpDeliveryDays;
                }

                if ($item->isDiamond()) {
                    $item->getDiamond()->setPhotos();
                }
            }
            $services = new DiamondPaidServiceCollection;
        }

        /** @var Collection|OrderItemViewModel[] $auctionDiamonds */
        $auctionDiamonds = $cartItems->filter->isAuctionDiamond();

        $user = user();

        // Сначала проходимся по лотам обычных аукционов (не PB)
        $auctions = [];
        $tmpLots = [];
        foreach ($auctionDiamonds as $auctionDiamond) {
            $auctionDiamondLot = $auctionDiamond->getDiamond()->auctionLot;
            if (!$auctionDiamondLot) {
                continue;
            }

            $auctionModel = $auctionDiamondLot->auction;
            if (!$auctionModel) {
                continue;
            }

            if (array_key_exists($auctionModel->getId(), $auctions)) {
                $auction = $auctions[$auctionModel->getId()];
            } else {
                $auction = (new Auction($auctionModel));
            }

            $lot = null;
            if (array_key_exists($auctionDiamondLot->getId(), $tmpLots)) {
                $lot = $tmpLots[$auctionDiamondLot->getId()];
            } else {
                $lotBid = null;
                if ($tmpBids = $auctionDiamondLot->getBids()) {
                    $lotBid = $tmpBids->first(
                        function (LotBid $bid) use ($user) {
                            return $user && $user->getId() === $bid->getUserId() && $bid->status->isActive();
                        }
                    );
                }

                /**
                 * @todo По логике, лот без ставок юзера передавать не нужно, но не уверен в этом.
                 * В любом случае, раньше до этого места не доходило из-за фатальной ошибки.
                 */
                if ($lotBid) {
                    $lot = new AuctionLot($auctionDiamondLot);
                    $lot->setBid($lotBid);
                    $auction->pushLot($lot);
                    $tmpLots[$lot->getLot()->getId()] = $lot;
                }
            }

            if ($lot) {
                $lot->setOrderItemViewModel($auctionDiamond);
                $auctions[$auctionModel->getId()] = $auction;
            }
        }

        // Проходимся по лотам аукционов PB
        $auctionsPb = [];
        $tmpLots = [];
        foreach ($auctionDiamonds as $auctionDiamond) {
            $auctionDiamondLot = $auctionDiamond->getDiamond()->auctionPBLot;
            if (!$auctionDiamondLot) {
                continue;
            }

            $auctionModel = $auctionDiamondLot->auction;
            if (!$auctionModel) {
                continue;
            }

            if (array_key_exists($auctionModel->getId(), $auctionsPb)) {
                $auction = $auctionsPb[$auctionModel->getId()];
            } else {
                $auction = (new AuctionPB($auctionModel));
            }

            $lot = null;
            if (array_key_exists($auctionDiamondLot->getId(), $tmpLots)) {
                $lot = $tmpLots[$auctionDiamondLot->getId()];
            } else {
                $lotBid = null;
                if ($tmpBids = $auctionDiamondLot->getBids()) {
                    $lotBid = $tmpBids->first(
                        function (PBLotBid $bid) use ($user) {
                            return $user && $user->getId() === $bid->getUserId() && $bid->status->isActive();
                        }
                    );
                }

                /**
                 * @todo По логике, лот без ставок юзера передавать не нужно, но не уверен в этом.
                 * В любом случае, раньше до этого места не доходило из-за фатальной ошибки.
                 */
                if ($lotBid) {
                    $lot = new AuctionPBLot($auctionDiamondLot);
                    $lot->setBid($lotBid);
                    $auction->pushLot($lot);
                    $tmpLots[$lot->getLot()->getId()] = $lot;
                }
            }
            if ($lot) {
                $lot->setOrderItemViewModel($auctionDiamond);
                $auctionsPb[$auctionModel->getId()] = $auction;
            }
        }

        return [
            'currency' => Currency::getCurrentCurrency(),
            'cart_cost' => $cartPrice,
            'cart_item_cost' => $cartItemPrice,
            'cart_service_cost' => $cartServicePrice,
            'cart_service_delivery_days' => $cartServiceDeliveryDays,
            'services' => $services,
            'basket_items' => $cartItems,
            'jewelry_items' => $cartItems->filter->isJewelry(),
            'diamond_items' => $cartItems->filter->isDiamond(),
            'constructor_items' => $cartItems->filter->isConstructorReadyProduct(),
            'auctions' => $auctions,
            'auctionsPB' => $auctionsPb,
            'float_cart_price' => $cartPrice,
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
        if (null === $oldFUserId){
            return;
        }

        Loader::IncludeModule('sale');
        $cartItems = db()->query('SELECT ID FROM b_sale_basket WHERE FUSER_ID = ' . $oldFUserId);
        while ($item = $cartItems->fetch()) {
            db()->query('UPDATE b_sale_basket SET FUSER_ID = ' . CSaleUser::getFUserCode() . ' WHERE ID = ' . $item['ID']);
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
        return BitrixBasketItem::filter(['ID' => $cartItemsIds])->with('jewelryConstructorReadyProduct.diamonds')->getList();
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

    /**
     * Определяет есть ли в корзине хотя бы один товар из конструктора.
     *
     * @param BasketBase $basket
     *
     * @return bool
     * @throws ArgumentNullException
     */
    public static function hasConstructorProductInBasket(BasketBase $basket): bool
    {
        $productIds = [];

        /** @var BasketItem $item */
        foreach ($basket->getBasketItems() as $item) {
            $productId = $item->getProductId();

            if ($productId) {
                $productIds[] = $productId;
            }
        }

        if (empty($productIds)) {
            return false;
        }

        return JewelryConstructorReadyProduct::filter(['ID' => $productIds])->limit(1)->count() > 0;
    }
}

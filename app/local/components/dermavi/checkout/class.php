<?php

namespace App\Local\Component;

use App\Components\ExtendedComponent;
use App\Core\Currency\Currency;
use App\Core\Delivery\DeliveryCalculator;
use App\Core\Sale\Entity\CartType\AuctionsCartType;
use App\Core\Sale\Entity\CartType\CartTypeInterface;
use App\Core\Sale\Entity\CartType\DefaultCartType;
use App\Core\Sale\PaySystem;
use App\Core\Sale\UserCart;
use App\Core\Sale\View\OrderItemViewModel;
use App\Core\SprintOptions\OrderSettings;
use App\Core\User\User;
use App\EventHandlers\CartHandlers;
use App\Helpers\LanguageHelper;
use App\Helpers\PriceHelper;
use App\Helpers\TTL;
use App\Helpers\UserCartHelper;
use App\Models\Catalog\ProductFactory;
use App\Models\Catalog\ProductInterface;
use App\Models\Delivery\DeliveryPickpoint;
use App\Models\HL\Country;
use App\Models\Sale\DeliveryTime;
use App\Models\Sale\PickupPoint;
use App\Models\Sale\PickupTime;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Service\GeoIp\Manager;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketBase;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Delivery\Services\Manager as DeliveryServicesManager;
use Bitrix\Sale\PaySystem\Manager as PaySystemManager;
use Bitrix\Sale\Order;
use Bitrix\Sale\Services\Base\RestrictionManager;
use Illuminate\Support\Collection;
use DateTime;
use App\Models\ForCustomers\Info;

/**
 * Компонент для вывода формы заказа.
 * Class SaleCheckoutComponent
 * @package App\Local\Component
 */
class CheckoutComponent extends ExtendedComponent
{
    public const DEFAULT_LOCATION = '0000495241';

    public const DEFAULT_PERSON_TYPE = 1;


    /** @var array|array[] $params Массив, описывающий настройки параметров компонента */
    protected $params = [
        'LOCATION'    => ['type' => 'int', 'default' => self::DEFAULT_LOCATION],
        'PERSON_TYPE' => ['type' => 'bool', 'default' => self::DEFAULT_PERSON_TYPE]
    ];


    /**
     * Запускает компонент.
     * @throws ArgumentException
     * @throws \Exception
     */
    public function execute(): void
    {
        $this->requireAuthorizedUser();

        $cartType = new DefaultCartType();

        /** @var UserCart $cart - Корзина пользователя */
        $cart = $this->getCart($cartType);
        if ($cart->getCart()->isEmpty()) {
            LocalRedirect('/personal/cart/');
        }

        foreach (UserCart::getUserCart(new DefaultCartType())->getBasketItems() as $item) {
            /** @var BasketItem $item - элемент корзины */
        }

        $cartOverallInfo = UserCartHelper::loadCartInfo($cartType);

        $deliveryServices = $this->getDeliveryServicesWithRestriction($cart->getCart());

        $paySystems = $this->getPaymSystemsWithRestriction($cart->getCart());

        $cartPriceFloat = $cartOverallInfo['float_cart_price'] + $cartOverallInfo['cart_service_cost'];

        $this->arResult = [
            'cart'                 => $cart,
            'cartItemsCost'        => $cartOverallInfo['cart_item_cost'],
            'servicesPrice'        => $cartOverallInfo['cart_service_cost'],
            'servicesDeliveryDays' => $cartOverallInfo['cart_service_delivery_days'],
            'cartPriceFloat'       => $cartPriceFloat,
            'currency'             => Currency::getCurrentCurrency(),
            'languageVersion'      => LanguageHelper::getLanguageVersion(),
            'user'                 => user(),
            'basketItems'          => $cartOverallInfo['basket_items'],
            'isCartChanged'        => $cart->isCartChanged(),
            'deliveryServices'     => $deliveryServices,
            'paySystems'           => $paySystems,
            'ip'                   => Manager::getRealIp(),
            'zip'                   => user()->getZip()

        ];

        $this->includeComponentTemplate();
    }

    /**
     * Доступна ли онлайн оплата для заказа
     * @param $cartOverallInfo
     *
     * @return bool
     */
    public function isBuyAvailable($cartOverallInfo): bool
    {
        return true;
    }

    /**
     * Загружаем данные по корзине пользователя
     *
     * @param CartTypeInterface $cartType Тип корзины
     *
     * @return UserCart
     * @throws \Bitrix\Main\ArgumentNullException
     */
    protected function getCart(CartTypeInterface $cartType): UserCart
    {
        // Отключил кеширование. Зачем оно здесь?
        $useCache = false;
        if ($useCache) {
            return cache(
                CartHandlers::getCacheInitDir() . LanguageHelper::getLanguageVersion(),
                TTL::DAY,
                static function () use ($cartType) {
                    return UserCart::getInstance($cartType);
                },
                CartHandlers::getCacheInitDir()
            );
        }

        return UserCart::getInstance($cartType);
    }

    protected function getDeliveryServicesWithRestriction(BasketBase $basket)
    {
        $order = Order::create(SITE_ID, user()->getId());
        $order->setPersonTypeId($this->arParams['PERSON_TYPE']);
        $order->setBasket($basket);
        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();
        $propertyCollection = $order->getPropertyCollection();
        $propertyLocation = $propertyCollection->getDeliveryLocation();
        $propertyLocation->setField('VALUE', $this->arParams['LOCATION']);
        $deliveryList = DeliveryServicesManager::getRestrictedList(
            $shipment,
            RestrictionManager::MODE_CLIENT
        );
        return $deliveryList;
    }

    protected function getPaymSystemsWithRestriction($basket)
    {
        $order = Order::create(SITE_ID, user()->getId());
        $order->setPersonTypeId($this->arParams['PERSON_TYPE']);
        $order->setBasket($basket);
        $paymentCollection = $order->getPaymentCollection();
        $payment = $paymentCollection->createItem();
        $paymentList = PaySystemManager::getListWithRestrictions(
            $payment,
            RestrictionManager::MODE_CLIENT
        );
        return $paymentList;
    }
}

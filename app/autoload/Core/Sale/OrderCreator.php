<?php

namespace App\Core\Sale;

use App\Core\Delivery\CccbDeliveryService;
use App\Core\Delivery\DeliveryCalculator;
use App\Core\Sale\Entity\CartType\CartTypeInterface;
use App\Core\Sale\Entity\OrderForm;
use App\EventHandlers\OrderHandlers;
use App\Exceptions\EmptyOrderCreateException;
use App\Helpers\OrderHelper;
use App\Models\Catalog\Catalog;
use App\Models\User;
use Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\NotImplementedException;
use Bitrix\Main\NotSupportedException;
use Bitrix\Main\ObjectException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Delivery\CalculationResult;
use Bitrix\Sale\Order;
use Bitrix\Sale\PaySystem\Manager as PaySystemManager;
use Elasticsearch\Endpoints\Count;
use Exception;
use Throwable;

/**
 * Класс для создания заказа
 * Class OrderCreator
 * @package App\Core\Sale
 */
class OrderCreator
{
    /** @var CartTypeInterface $cartType Тип корзины */
    private $cartType;

    /** @var User $user - Пользователь */
    private $user;

    /** @var Order $order - Заказ */
    private $order;

    /** @var OrderForm $orderForm - Свойства заказа */
    private $orderForm;

    /** @var Basket $cart - Корзина */
    private $cart;

    /** @var string $comment - Комментарий к заказу */
    private $comment;

    /**
     * Объявляем метод приватным для работы синглтона
     * OrderCreator constructor.
     *
     * @param User $user - Пользователь, на которого оформляется заказ
     * @param OrderForm $orderFields - Свойства заказа
     * @param string $comment - Комментарий к заказу
     * @param CartTypeInterface $cartType Тип корзины
     *
     * @throws LoaderException
     */
    public function __construct(User $user, OrderForm $orderFields, string $comment, CartTypeInterface $cartType)
    {
        Loader::includeModule('catalog');
        Loader::includeModule('sale');

        $this->user = $user;
        $this->orderForm = $orderFields;
        $this->comment = $comment;
        $this->cartType = $cartType;
    }

    /**
     * Создаем заказ
     *
     * @return int
     *
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentTypeException
     * @throws NotImplementedException
     * @throws NotSupportedException
     * @throws ObjectException
     * @throws ObjectNotFoundException
     * @throws BasketEmptyException
     * @throws Exception
     */
    public function createOrder(): int
    {
        $this->isEmptyBasketException();

        /** @var Order order - Созданный заказ */
        $this->order = Order::create(
            $this->cartType->getSiteId(),
            $this->user->getId(),
            $this->cartType->getCurrency()->getSymCode());

        /** Устанавливаем тип плательщика */
        $this->order->setPersonTypeId(
            PersonType::getPersonType(
                    PersonType::PHYSICAL_ENTITY
            )->getPersonTypeId()
        );

        /** Устанавливаем валюту */
        $this->order->setField('CURRENCY', $this->cartType->getCurrency()->getSymCode());

        /** Устанавливаем комментарий */
        if ($this->comment) {
            $this->order->setField('USER_DESCRIPTION', $this->comment);
        }

        $this->order->setBasket($this->getCart());
        $this->initShipment();
        $this->initPayment();
        $this->setProperties();

        $this->order->doFinalAction(true);
        $result = $this->order->save();
        $orderId = $this->order->getId();

        if (!$orderId) {
            $errorMessage = implode('; ', $result->getErrorMessages());
            logger('api')->error(__CLASS__ . ': ' . 'Failed to create order. ' . $errorMessage);
        }

        if ($orderId) {
            OrderHandlers::sendMessagesAfterOrderCreate($orderId, $this->cartType);
        }

        return $orderId;
    }

    /**
     * @param Basket $cart
     * @return static
     */
    public function setCart(Basket $cart)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * @return Basket
     * @throws ArgumentNullException
     * @throws ExceptionFromBitrix
     */
    protected function getCart(): Basket
    {
        if ($this->cart === null) {
            $this->cart = $this->createUserCart();
        }

        return $this->cart;
    }

    /**
     * Генерация корзины по текущему юзеру
     *
     * @return Basket
     * @throws ArgumentNullException
     * @throws ExceptionFromBitrix
     */
    private function createUserCart(): Basket
    {
        $cart = UserCart::getUserCart($this->cartType);

        $diamondsIds = [];
        /** @var BasketItem $item */
        foreach ($cart->getBasketItems() as $item) {
            $diamondsIds[] = $item->getField('PRODUCT_ID');
        }
        if ($diamondsIds) {
            $reload = false;
            /** @var \Illuminate\Support\Collection|Diamond[] $diamonds - Коллекция бриллиантов */
            $diamonds = Diamond::filter(['ID' => $diamondsIds])->getList();
            foreach ($diamonds as $diamond) {
                // @todo Весьма сомнительный участок
                if (!$diamond->isAvailableForSelling()) {
                    UserCart::removeFromCart($diamond->getID());
                    $reload = true;
                }
            }
            if ($reload) {
                $cart = UserCart::getUserCart($this->cartType);
            }
        }

        return $cart;
    }

    /**
     * Создаем отгрузку и устанавливаем способ доставки
     *
     * @return void
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentTypeException
     * @throws NotSupportedException
     * @throws ObjectNotFoundException
     * @throws Exception
     */
    private function initShipment(): void
    {
        $shipmentCollection = $this->order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();

        $deliveryServiceId = $this->orderForm->getDeliveryServiceId();

        $delivery = $deliveryServiceId
            ? DeliveryServiceWrapper::findById($deliveryServiceId)
            : DeliveryServiceWrapper::getEmptyDelivery();

        if ($delivery && $delivery->getService() instanceof CccbDeliveryService) {
            try {
                (new DeliveryCalculator())->calculateConcrete(
                    $this->order->getPrice(),
                    $this->orderForm->getDeliveryCity(),
                    $this->orderForm->isToDoor()
                );
            } catch (Exception | Throwable $exception) {
                $result = new CalculationResult();
                $result->setDeliveryPrice(0);
            }
        }

        $shipment->setFields([
            'DELIVERY_ID' => $delivery ? $delivery->getId() : 0,
            'DELIVERY_NAME' => $delivery ? $delivery->getName() : '',
            'CURRENCY' => CurrencyManager::getBaseCurrency(),
            'CUSTOM_PRICE_DELIVERY' => 'Y', // Чтобы цена не сбрасывалась в 0 при изменении отгрузки
        ]);

        $shipmentItemCol = $shipment->getShipmentItemCollection();

        /** @var BasketItem $item */
        foreach ($this->getCart()->getBasketItems() as $item) {
            $shipmentItem = $shipmentItemCol->createItem($item);
            $shipmentItem->setQuantity($item->getQuantity());
        }
    }

    /**
     * Создаем оплату
     *
     * @return void
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws NotSupportedException
     * @throws ExceptionFromBitrix
     */
    private function initPayment(): void
    {
        $paymentCollection = $this->order->getPaymentCollection();
        $payment = $paymentCollection->createItem();
        //todo Убрать получение идентификатора платежной системы "Внутренний счёт", используемой в качестве заглушки, когда будет готова корзина B2C
        $paySystemId = $this->orderForm->getPaySystemId() ? : PaySystem::getPaySystem('INNER')->getId();
        $paySystemService = PaySystemManager::getObjectById($paySystemId);
        $payment->setFields([
            'PAY_SYSTEM_ID' => $paySystemService ? $paySystemService->getField('ID') : '',
            'PAY_SYSTEM_NAME' => $paySystemService ? $paySystemService->getField('NAME') : '',
            'SUM' => $this->getCart()->getPrice() + $this->order->getDeliveryPrice(),
        ]);
    }

    /**
     * Устанавливаем свойства заказа
     *
     * @return void
     * @throws ArgumentException
     * @throws NotImplementedException
     */
    private function setProperties(): void
    {
        $mapProperties = OrderForm::getMapProperties();
        /** Задаем свойства заказу */
        foreach ($this->orderForm->getAllProperties() as $property => $value) {
            if ($mapProperties[$property] && $value) {
                OrderHelper::setProperty($this->order, $mapProperties[$property], $value);
            }
        }
    }

    /**
     * Увеличиваем ID следующего заказа
     *
     * @param int $orderId
     * @param int $incValue
     *
     * @throws \Bitrix\Main\Db\SqlQueryException
     * @return void
     */
    public function incNextOrderId(int $orderId, int $incValue = 0): void
    {
        if (!$incValue) {
            $incValue = rand(5, 15);
        }
        $nextOrderId = $incValue + $orderId;
        Application::getConnection()
                   ->query('ALTER TABLE `b_sale_order` AUTO_INCREMENT = ' . $nextOrderId);
    }

    /** проверка корзины */
    private function isEmptyBasketException(): void
    {
        if (empty($this->getCart()->getBasketItems())) {
            throw new EmptyOrderCreateException();
        }
    }
}

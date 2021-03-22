<?php

namespace App\Helpers;

use App\Core\Sale\OrderMail\AuctionDiamondOrder;
use App\Core\Sale\OrderMail\DiamondOrder;
use App\Core\Sale\OrderMail\JewelryConstructorReadyProduct;
use App\Core\Sale\OrderMail\JewelryOrder;
use App\Core\Sale\OrderMail\ProductOrder;
use App\Core\Sale\OrderMail\ProductOrderInterface;
use App\Core\Sale\View\OrderItemViewModel;
use App\Core\Sale\View\OrderViewModel;
use App\Models\Auxiliary\Sale\BitrixBasketItem;
use App\Models\Auxiliary\Sale\BitrixBasketItemProperty;
use App\Models\Auxiliary\Sale\BitrixOrder;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Delivery\Services\Manager;
use Bitrix\Sale\PaySystem\Manager as PaySystemManager;
use Bitrix\Sale\Order;
use Bitrix\Sale\PropertyValueBase;
use Bitrix\Sale\Shipment;
use Exception;
use Bitrix\Main\NotImplementedException;

/** @noinspection PhpUnhandledExceptionInspection */
Loader::includeModule('sale');

/**
 * Класс-хелпер для работы с заказами
 * Class OrderHelper
 * @package App\Helpers
 */
class OrderHelper
{
    /**
     * Устанавливаем заказу свойства
     *
     * @param Order $order - Заказ
     * @param string $propertyCode - Символьный код свойства
     * @param mixed $propertyValue - Значение свойства
     * @throws ArgumentException
     * @throws NotImplementedException
     */
    public static function setProperty(Order &$order, string $propertyCode, string $propertyValue): void
    {
        foreach ($order->getPropertyCollection() as $property) {
            /* @var PropertyValueBase $property */
            if ($property->getField('CODE') == $propertyCode) {
                $property->setValue($propertyValue);
                break;
            }
        }
    }

    /**
     * Возвращает массив наименований служб доставки. В качестве ключей используются идентификаторы служб.
     * @return array
     */
    public static function getDeliveryServiceNames(): array
    {
        $names = [];
        try {
            $services = Manager::getActiveList();
            foreach ($services as $serviceId => $service) {
                $names[$serviceId] = $service['NAME'];
            }
        } catch (ArgumentException $e) {
            // Игнорируем
        }
        return $names;
    }

    /**
     * Возвращает id службы доставки по имени
     *
     * @param $name
     * @return \Arrilot\BitrixCacher\CacheBuilder|mixed
     */
    public static function getDeliveryIdByName($name)
    {
        $cacheKey = get_default_cache_key(self::class) . $name;

        return cache(
            $cacheKey,
            TTL::WEEK,
            function () use ($name) {
                try {
                    $services = Manager::getActiveList();
                    foreach ($services as $serviceId => $service) {
                        if ($service['NAME'] == $name) {
                            return $serviceId;
                        }
                    }
                } catch (ArgumentException $e) {
                    // Игнорируем
                }

                return 0;
            }
        );
    }

    /**
     * Возвращает объект с данными о доставке для заказа.
     * @param Order $order
     * @return null|Shipment
     * @throws Exception
     */
    public static function getShipment(Order $order): ?Shipment
    {
        /** @var Shipment $shipment */
        foreach ($order->getShipmentCollection() as $shipment) {
            if (!$shipment->isSystem()) {
                return $shipment;
            }
        }
        return null;
    }

    /**
     * Добавляет позицию заказа в доставку.
     * @param BasketItem $basketItem
     * @throws Exception
     */
    public static function add2Shipment(BasketItem $basketItem): void
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $shipment = self::getShipment($basketItem->getCollection()->getBasket()->getOrder());
        if (!$shipment) {
            return;     // У заказа нет еще доставки
        }

        $item = $shipment->getShipmentItemCollection()->createItem($basketItem);
        $item->setQuantity($basketItem->getQuantity());
        $item->save();
    }

    /**
     * Удаляет товар из отгрузки
     *
     * @param Order $order - Заказ
     * @param int $cartItemId - Идентификатор товара в корзине
     * @return void
     */
    public static function removeFromShipment(Order $order, int $cartItemId): void
    {
        $shipmentCollection = $order->getShipmentCollection();
        foreach ($shipmentCollection as $shipment) {
            foreach ($shipment->getShipmentItemCollection() as $item) {
                if ($item->getField('BASKET_ID') == $cartItemId) {
                    $item->delete();
                    $shipment->save();
                    break;
                }
            }
        }
    }

    /**
     * Обновляет сумму оплаты
     *
     * @param Order $order - Заказ
     * @return void
     */
    public static function refreshPayment(Order $order): void
    {
        $paymentCollection = $order->getPaymentCollection();
        $paySystemId = null;
        foreach ($paymentCollection as $payment) {
            $paySystemId = $payment->getPaymentSystemId();
            $payment->delete();
        }

        $paySystemService = PaySystemManager::getObjectById($paySystemId);
        $payment = $paymentCollection->createItem();
        $payment->setFields(
            [
                'PAY_SYSTEM_ID'   => $paySystemService->getField('ID'),
                'PAY_SYSTEM_NAME' => $paySystemService->getField('NAME'),
                'SUM'             => $order->getBasket()->getPrice()
            ]
        );
        $order->save()->getErrors();
    }

    /**
     * Убирает для всех бриллиантов в заказев инфоблоке Имя бриллианта и Сообщение
     * @param string $orderId
     */
    public static function removeDiamondsNamesAndMessages(string $orderId): void
    {
        $order = OrderViewModel::fromOrderIds([$orderId])->first();

        $items = $order->getItems();
        foreach ($items as $item) {
            self::removeDiamondNameAndMessage($item);
        }
    }

    /**
     * Устанавливает в инфоблоке Имя бриллианта и Сообщение
     * @param OrderItemViewModel $basketItem
     */
    public static function setDiamondNameAndMessage(OrderItemViewModel $basketItem): void
    {
        $diamond = $basketItem->getDiamond();
        if ($diamond) {
            $diamond->update(
                [
                    'PROPERTY_DIAMOND_NAME_VALUE'    => $basketItem->getCustomProperty('NAME'),
                    'PROPERTY_TRACING_MESSAGE_VALUE' => $basketItem->getCustomProperty('DESCRIPTION')
                ]
            );
        }
    }

    /**
     * Удаляет в инфоблоке Имя бриллианта и Сообщение
     * @param OrderItemViewModel $basketItem
     */
    public static function removeDiamondNameAndMessage(OrderItemViewModel $basketItem): void
    {
        $diamond = $basketItem->getDiamond();
        if ($diamond) {
            $diamond->update(
                [
                    'PROPERTY_DIAMOND_NAME_VALUE'    => '',
                    'PROPERTY_TRACING_MESSAGE_VALUE' => ''
                ]
            );
        }
    }

    /**
     * Возвращает класс типа обработчика письма о заказе
     *
     * @param BitrixOrder $order
     *
     * @return ProductOrderInterface
     */
    public static function getMode(BitrixOrder $order): ProductOrderInterface
    {
        $mode = new ProductOrder();
        return $mode;
    }
}

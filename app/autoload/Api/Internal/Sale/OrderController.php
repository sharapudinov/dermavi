<?php

namespace App\Api\Internal\Sale;

use App\Api\BaseController;
use App\Core\BitrixEvent\EventMessage;
use App\Core\Currency\Currency;
use App\Core\Sale\Entity\CartType\AuctionsCartType;
use App\Core\Sale\Entity\CartType\DefaultCartType;
use App\Core\Sale\Entity\OrderForm;
use App\Core\Sale\OrderCreator;
use App\Core\Sale\PaySystem;
use App\Core\Sale\PaytureService;
use App\Core\Sale\UserCart;
use App\Core\Sale\View\OrderItemViewModel;
use App\Core\Sale\View\OrderStatusViewModel;
use App\Core\Sale\View\OrderViewModel;
use App\Core\SprintOptions\OrderSettings;
use App\EventHandlers\OrderHandlers;
use App\Exceptions\EmptyOrderCreateException;
use App\Exceptions\UserNotAuthorizedException;
use App\Helpers\OrderHelper;
use App\Helpers\PriceHelper;
use App\Helpers\UserCartHelper;
use App\Models\Auxiliary\Sale\BitrixBasketItem;
use App\Models\Auxiliary\Sale\BitrixBasketItemProperty;
use App\Models\Auxiliary\Sale\BitrixOrder;
use App\Models\Catalog\Catalog;
use App\Models\Catalog\HL\PaidServiceCategory;
use App\Models\Catalog\PaidService;
use App\Models\Catalog\PaidServices\EngravingService;
use App\Models\Catalog\ProductFactory;
use App\Models\Catalog\ProductInterface;
use App\Models\User;
use Arrilot\BitrixCacher\Cache;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\NotImplementedException;
use Bitrix\Main\NotSupportedException;
use Bitrix\Main\ObjectException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Order;
use CEvent;
use CModule;
use CSaleBasket;
use CSaleOrder;
use Exception;
use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use LogicException;
use Psr\Http\Message\ResponseInterface;

/**
 * Класс-контроллер для работы с заказами
 * Class OrderController
 * @package App\Api\Internal\Main
 */
class OrderController extends BaseController
{
    /**
     * OrderController constructor.
     * @param ContainerInterface $container
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        Loader::includeModule('sale');
    }

    /**
     * Получает позицию заказа
     *
     * @param int $orderItemId - Идентификатор позиции в корзине
     * @return OrderItemViewModel
     */
    private function getOrderItem(int $orderItemId): OrderItemViewModel
    {
        /** @var OrderViewModel $order - Заказ */
        $order = OrderViewModel::fromOrderIds([BitrixBasketItem::getById($orderItemId)->getOrderId()]);

        /** @var OrderItemViewModel $orderItem - Позиция заказа */
        return $order->first()->getItems()->filter(
            function (OrderItemViewModel $item) use ($orderItemId) {
                return $item->getBasketId() == $orderItemId;
            }
        )->first();
    }

    /**
     * Создать заказ
     *
     * @return ResponseInterface
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    public function createOrder(): ResponseInterface
    {
//        try {
        $user = user();
        if (!$user) {
            return $this->respondWithError((new UserNotAuthorizedException())->getMessage());
        }
        $cartType = new DefaultCartType();

        $orderFields = new OrderForm();
        $request = context()->getRequest();
        $userCart = UserCart::getInstance($cartType);

        // Если в корзине пользователя есть товар, который кто-то купил и который мы удалили, выводим ошибку
        if ($userCart && $userCart->isCartChanged()) {
            return $this->respondWithError('Some items from your cart have been deleted!', 410);
        }

        $orderCart = $userCart->getCart();

        // Заполняем общие свойства для доставки и самовывоза
        $orderFields
            ->setDeliveryServiceId((int)$request->get('DELIVERY_SERVICE_ID'))
            ->setPaySystemId((int)$request->get('PAY_SYSTEM_ID'))
            ->setDeliveryFirstName(ucfirst((string)$request->get('DELIVERY_FIRST_NAME')))
            ->setDeliveryLastName(ucfirst((string)$request->get('DELIVERY_LAST_NAME')))
            ->setDeliveryFIO(
                ucfirst(
                    (string)$request->get('DELIVERY_FIRST_NAME') . ' ' .
                    $request->get('DELIVERY_SECOND_NAME') . ' ' .
                    $request->get('DELIVERY_LAST_NAME')
                )
            )
            ->setDeliverySecondName(ucfirst((string)$request->get('DELIVERY_SECOND_NAME')))
            ->setDeliveryBirthday((string)$request->get('DELIVERY_BIRTHDAY'))
            ->setDeliveryDate((string)$request->get('DELIVERY_DATE'))
            ->setDeliveryTime((string)$request->get('DELIVERY_TIME'))
            ->setToDoor((bool)$request->get('DELIVERY_TO_DOOR'))
            ->setEmail((string)$request->get('ORDER_EMAIL'));


        $orderFields
            ->setDeliveryZip((string)$request->get('DELIVERY_ZIP'))
            ->setDeliveryRegion((string)$request->get('DELIVERY_REGION'))
            ->setDeliveryCity((string)$request->get('DELIVERY_CITY'))
            ->setDeliveryStreet((string)$request->get('DELIVERY_STREET'))
            ->setDeliveryHouse((string)$request->get('DELIVERY_HOUSE'))
            ->setDeliveryFlat((string)$request->get('DELIVERY_FLAT'))
            ->setDeliveryPhone((string)$request->get('DELIVERY_PHONE'))
            ->setBillingZip((string)$request->get('BILLING_ZIP'))
            ->setBillingRegion((string)$request->get('BILLING_REGION'))
            ->setBillingCity((string)$request->get('BILLING_CITY'))
            ->setBillingStreet((string)$request->get('BILLING_STREET'))
            ->setBillingHouse((string)$request->get('BILLING_HOUSE'))
            ->setBillingFlat((string)$request->get('BILLING_FLAT'))
            ->setBillingFirstName((string)$request->get('BILLING_FIRST_NAME'))
            ->setBillingLastName((string)$request->get('BILLING_LAST_NAME'))
            ->setBillingSecondName((string)$request->get('BILLING_SECOND_NAME'))
            ->setBillingBirthday((string)$request->get('BILLING_BIRTHDAY'))
            ->setBillingPhone((string)$request->get('BILLING_PHONE'))
            ->setDeliveryPickupPoint($request->get('DELIVERY_PICKUP_POINT'));

        // Получаем пользовательский комментарий к заказу
        $userDescription = $request->get('USER_DESCRIPTION') ?? '';

        try {
            $orderId = (new OrderCreator($user, $orderFields, $userDescription, $cartType))
                ->setCart($orderCart)
                ->createOrder();
        } catch (EmptyOrderCreateException $e) {
            return $this->respondWithError($e->getMessage());
        }

        CModule::IncludeModule('sale');
        $res = CSaleBasket::GetList([], ["ORDER_ID" => $orderId]); // ID заказа
        while ($arItem = $res->Fetch()) {
            $items[] = $arItem['PRODUCT_ID'];
        }

        if ($arOrder = CSaleOrder::GetByID($orderId)) {
            $order_price = $arOrder['PRICE'];
        }

        $json = [
            'transaction_id'  => $orderId,
            'value'           => ceil($order_price), // ценность события
            'shipping'        => 0, // стоимость доставки
            'items'           => $items, // список ID товаров
            'checkout_option' => '' // способ оплаты
        ];


        return $this->respondWithSuccess($orderId, $json);
    }

    /**
     * Отменяет заказ
     * @param int $orderId - идентификатор заказа
     * @return ResponseInterface
     */
    public function cancelOrder(int $orderId): ResponseInterface
    {
        try {
            $order = Order::load($orderId);
            if ($this->canCancel($order)) {
                $order->setField('CANCELED', 'Y');
                $order->save();

                /** @var \App\Core\BitrixEvent\Entity\EventMessage $eventMessage - Почтовое событие */
                $eventMessage = EventMessage::getEventMessagesByCode('ORDER_CANCEL_MANAGER', 'ru')
                    ->first();

                CEvent::SendImmediate(
                    $eventMessage->getEventName(),
                    's2',
                    [
                        'ORDER_ID'   => $order->getId(),
                        'ORDER_LINK' => get_external_url(false)
                            . '/bitrix/admin/sale_order_view.php?ID=' . $order->getId(
                            ) . '&filter=Y&set_filter=Y&lang=ru',
                    ],
                    'Y',
                    $eventMessage->getMessageId(),
                    [],
                    'ru'
                );


                OrderHelper::removeDiamondsNamesAndMessages($orderId);
                return $this->respondWithSuccess();
            } else {
                return $this->respondWithError('Order can not be canceled');
            }
        } catch (\Exception $exception) {
            return $this->respondWithError($exception->getMessage());
        }
    }

    /**
     * Проверяет, можно ли отменить заказ
     * @param Order $order
     * @return bool
     */
    private function canCancel(Order $order): bool
    {
        if ($order->isPaid() ||
            $order->isCanceled() ||
            $order->getUserId() != User::current()->getId()
        ) {
            return false;
        }

        return true;
    }

    /**
     * Устанавливает пользовательские свойства позиции заказа.
     * @return ResponseInterface
     */
    public function setOrderItemCustomProperties(int $orderItemId): ResponseInterface
    {
        /** @var OrderItemViewModel $orderItem - Позиция в заказе */
        $orderItem = $this->getOrderItem($orderItemId);
        if ($orderItem->getCustomProperty('NAME') == ''
            && $orderItem->getCustomProperty('DESCRIPTION') == '') {
            if ($orderItem->getProduct() instanceof Diamond) {
                $orderUpdateReason = 'В заказ были добавлены имя бриллианта и послание для бриллианта '
                    . $orderItem->getDiamond()->getPacketNumber();
            } else {
                $orderUpdateReason = 'В заказ были добавлены имя бриллианта и послание для изделия '
                    . $orderItem->getCombination()->getId();
            }
        } else {
            if ($orderItem->getProduct() instanceof Diamond) {
                $orderUpdateReason = 'В заказ были изменены имя бриллианта и послание для бриллианта '
                    . $orderItem->getDiamond()->getPacketNumber();
            } else {
                $orderUpdateReason = 'В заказ были изменены имя бриллианта и послание для изделия '
                    . $orderItem->getCombination()->getId();
            }
        }

        try {
            /** @var User $user - Текущий пользователь */
            $user = User::current();
            if (!$user->isAuthorized()) {
                return $this->errorUnauthorized();
            }

            /** @var UserCart $cart - Корзина пользователя */
            $cart = UserCart::getInstance(new DefaultCartType());

            /** @var BitrixBasketItem $basketItem - Позиция заказа */
            $basketItem = $this->getBasketItem($orderItemId);

            /** @var BitrixOrder $order - Заказ */
            $order = BitrixOrder::getById($basketItem->getOrderId());

            //if ($order->getStatusId() != OrderStatusViewModel::STATUS_IN_PROCESS) {
            //   throw new Exception('Can not add property for non processing order');
            //}

            if (!$order) {
                return $this->respondWithError('Order ' . $basketItem->getOrderId() . ' is not found');
            }

            if ($order->getUserId() != $user->getId()) {
                return $this->errorForbidden();
            }

            /** @var array $params - Массив параметров с фронта */
            $params = $this->request->getParsedBody();

            $cart->applyBasketProperties(
                $basketItem,
                [
                    'NAME'        => (string)$params['name'],
                    'DESCRIPTION' => (string)$params['description']
                ]
            );

            OrderHelper::setDiamondNameAndMessage(new OrderItemViewModel($basketItem));
            OrderHandlers::notifyManagerAboutOrderUpdate($order, $orderUpdateReason);
            Cache::flush('TracingPlayer_' . $basketItem->getName());
            return $this->respondWithSuccess();
        } catch (Exception $exception) {
            return $this->respondWithError($exception->getMessage());
        }
    }

    /**
     * Удаляет пользовательские свойства из позиции заказа
     *
     * @param int $orderItemId
     * @return ResponseInterface
     */
    public function removeOrderItemCustomProperties(int $orderItemId): ResponseInterface
    {
        try {
            /** @var User $user - Текущий пользователь */
            $user = User::current();
            if (!$user->isAuthorized()) {
                return $this->errorUnauthorized();
            }

            /** @var UserCart $cart - Корзина пользователя */
            $cart = UserCart::getInstance(new DefaultCartType());

            /** @var BitrixBasketItem $basketItem - Позиция заказа */
            $basketItem = $this->getBasketItem($orderItemId);

            /** @var BitrixOrder $order - Заказ */
            $order = BitrixOrder::getById($basketItem->getOrderId());

            if (!$order) {
                return $this->respondWithError('Order ' . $basketItem->getOrderId() . ' is not found');
            }

            if ($order->getUserId() != $user->getId()) {
                return $this->errorForbidden();
            }

            $cart->applyBasketProperties(
                $basketItem,
                [
                    'NAME'        => '',
                    'DESCRIPTION' => ''
                ]
            );

            if ($basketItem->getProduct() instanceof Diamond) {
                $message = 'В заказе удалено имя и послание бриллианта ' . $basketItem->diamond->getPacketNumber();
            } else {
                $message = 'В заказе удалено имя и послание изделия ' . $basketItem->getProduct()->getId();
            }

            OrderHelper::removeDiamondNameAndMessage(new OrderItemViewModel($basketItem));
            OrderHandlers::notifyManagerAboutOrderUpdate(
                $order,
                $message
            );
            return $this->respondWithSuccess();
        } catch (Exception $exception) {
            return $this->respondWithError($exception->getMessage());
        };
    }

    /**
     * Добавляет услугу "Заказ сертификата".
     * @param int $orderItemId
     * @return ResponseInterface
     */
    public function addCertificateOrder(int $orderItemId): ResponseInterface
    {
        /** @var OrderItemViewModel $orderItem - Позиция в заказе */
        $orderItem = $this->getOrderItem($orderItemId);

        $product = $orderItem->getProduct();
        if ($product instanceof Diamond) {
            $message = 'В заказе был добавлен сертификат для бриллианта ' . $product->getPacketNumber();
        } else {
            $message = 'В заказе был добавлен сертификат для изделия ' . $product->getId();
        }

        return $this->addDiamondPaidService(
            $orderItemId,
            function (BasketItem $basketItem, ProductInterface $diamond) {
                $services = PaidService::findServicesForDiamond($diamond, PaidServiceCategory::CERTIFICATE);
                if ($services->isEmpty()) {
                    throw new LogicException("Available service is not found");
                }

                /** @var PaidService $service */
                $service = $services->first();

                $giaDiamond = null;
                if ($diamond instanceof Diamond) {
                    $giaDiamond = $diamond;
                } else {
                    $giaDiamond = $diamond->diamonds->first();
                }

                /** @var int $price - Цена на услугу в рублях */
                $price = PriceHelper::getPriceInSpecificCurrency(
                    UserCartHelper::getGiaCertificatePriceForDiamond($giaDiamond),
                    (new Currency)->getCurrencyByAlphabetCode(Currency::RUB_CURRENCY)
                );

                return UserCart::addPaidService($basketItem, $service, 1, [], $diamond, $price);
            },
            $message
        );
    }


    /**
     * Добавляет услугу "Гравировка".
     * @param int $orderItemId
     * @return ResponseInterface
     */
    public function addEngraving(int $orderItemId): ResponseInterface
    {
        /** Позиция в заказе */
        $orderItem = $this->getOrderItem($orderItemId);
        if ($orderItem->engraving) {
            $item = BitrixBasketItem::getById($orderItem->engraving->getBasketId());
            $item->delete();

            OrderHelper::removeFromShipment(
                Order::load($orderItem->getOrderId()),
                $orderItem->engraving->getBasketId()
            );
        }

        /** Гравировка */
        $engraving = $orderItem->getAttachedService('engraving');
        $type = $engraving ? 'изменен' : 'добавлен';

        if ($orderItem->isDiamond()) {
            $number = $orderItem->getDiamond()->getPacketNumber();
            $productType = 'бриллианта';
        } elseif ($orderItem->isJewelry()) {
            $number = $orderItem->getJewelrySku()->getCode();
            $productType = 'ювелирного изделия';
        }

        $orderUpdateReason = sprintf('В заказе был %s текст гравировки для %s %s', $type, $productType, $number);

        return $this->addDiamondPaidService(
            $orderItemId,
            function (BasketItem $basketItem, ProductInterface $product) {
                $params = $this->request->getParsedBody();
                return EngravingService::addEngraving($basketItem, $product, $params);
            },
            $orderUpdateReason
        );
    }

    /**
     * Добавляет платную услугу для бриллианта.
     * @param int $orderItemId
     * @param callable $processor
     * @param string $orderUpdateReason - Причина обновления заказа (текст для письма)
     * @return ResponseInterface
     */
    private function addDiamondPaidService(
        int $orderItemId,
        callable $processor,
        string $orderUpdateReason
    ): ResponseInterface {
        $user = User::current();

        if (!$user->isAuthorized()) {
            return $this->errorUnauthorized();
        }

//        try {
        $item = $this->getBasketItem($orderItemId);
        $product = ProductFactory::getById($item->getProductId());
        if (!$product) {
            return $this->respondWithError("It's not a product");
        }

        $order = Order::load($item->getOrderId());
        if (!$order) {
            return $this->respondWithError('Order not found');
        }

        $basketItem = $order->getBasket()->getItemById($item->getId());
        if ($serviceItem = $processor($basketItem, $product)) {
            OrderHelper::add2Shipment($serviceItem);
        }

        OrderHelper::refreshPayment($order);
        OrderHandlers::notifyManagerAboutOrderUpdate(BitrixOrder::getById($item->getOrderId()), $orderUpdateReason);

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->respondWithSuccess($serviceItem->getId());
//        } catch (Exception $exception) {
//            return $this->respondWithError($exception->getMessage());
//        }
    }

    /**
     * Удаляет платную услугу
     *
     * @param int $orderItemId - Идентификатор услуги в корзине
     * @return ResponseInterface
     */
    public function removePaidService(int $orderItemId): ResponseInterface
    {
        $user = User::current();

        if (!$user->isAuthorized()) {
            return $this->errorUnauthorized();
        }

        /** @var ResponseInterface $response - Ответ сервера */
        $response = null;
        try {
            /** @var BitrixBasketItem $item - Позиция заказа */
            $item = BitrixBasketItem::getById($orderItemId);
            $item->delete();

            $order = Order::load($item->getOrderId());
            if ($order->getField('STATUS_ID') != OrderStatusViewModel::STATUS_IN_PROCESS) {
                throw new Exception('Can not remove service if order not in process');
            }

            OrderHelper::refreshPayment($order);
            OrderHelper::removeFromShipment($order, $orderItemId);

            $order->save();
            $response = $this->respondWithSuccess();

            //Возможно это надо выбирать не через свойство, а через parentID (который сейчас не проставляется)
            $productId = $item->properties->filter(
                function (BitrixBasketItemProperty $property) {
                    return strtolower($property['NAME']) == 'diamond'
                        || strtolower($property['NAME'] == 'jewelry')
                        || strtolower($property['NAME'] == 'constructor');
                }
            )->first()->getValue();

            $text = '';
            if ($item->diamond) {
                $text = 'бриллианта';
            }
            if ($item->jewelry || $item->jewelryConstructorReadyProduct) {
                $text = 'ювелирного изделия';
            }
            OrderHandlers::notifyManagerAboutOrderUpdate(
                BitrixOrder::getById($item->getOrderId()),
                'Удалена услуга: ' . $item->getName() . ' для ' . $text . ' ' . $productId
            );
        } catch (Exception $exception) {
            $response = $this->respondWithError($exception->getMessage() . $exception->getLine());
        }

        return $response;
    }

    /**
     * Ищет позицию заказа по идентификатору.
     *
     * @param int $itemId
     * @return BitrixBasketItem
     */
    public function getBasketItem(int $itemId): BitrixBasketItem
    {
        $item = BitrixBasketItem::getById($itemId);
        if (!$item || $item->getOrderId() <= 0) {
            throw new InvalidArgumentException('Order item is not found');
        }
        return $item;
    }

}

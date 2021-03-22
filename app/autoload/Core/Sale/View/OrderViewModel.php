<?php
namespace App\Core\Sale\View;

use App\Core\Currency\Currency;
use App\Helpers\BitrixOrderStatusConstants;
use App\Helpers\DateTimeHelper;
use App\Helpers\NumberHelper;
use App\Helpers\UserCartHelper;
use App\Models\Auxiliary\Sale\BitrixBasketItem;
use App\Models\Auxiliary\Sale\BitrixDelivery;
use App\Models\Auxiliary\Sale\BitrixOrder;
use App\Models\Auxiliary\Sale\BitrixOrderStatus;
use App\Models\Sale\PickupPoint;
use Bitrix\Main\Type\DateTime;
use DateTime as PhpDateTime;
use Exception;
use Illuminate\Support\Collection;

/**
 * Модель представления заказа.
 * Class OrderViewModel
 * @package App\Core\Sale\History
 */
class OrderViewModel
{
    /** @var int Идентификатор заказа */
    private $orderId;

    /** @var int Номер заказа */
    private $orderAccountNumber;

    /** @var DateTime Дата заказа */
    private $date;

    /** @var OrderStatusViewModel Код статуса заказа */
    private $status;

    /** @var float Стоимость заказа */
    private $price;

    /** @var AddressViewModel Адрес доставки */
    private $deliveryAddress;

    /** @var PickupPoint Пункт самовывоза */
    private $pickupPoint;

  /** @var Collection Состав заказа */
    private $items;

    /** @var bool Заказ отменен? */
    private $isCanceled;

    /** @var string Номер отслеживания заказа */
    private $trackNumber;

    /** @var BitrixOrder Объект заказа Битрикса */
    private $order;

    /** @var string Комментарий пользователя к заказу */
    private $userDescription;

    /**
     * OrderViewModel constructor.
     * @param BitrixOrder $order
     * @param null|Collection|OrderItemViewModel[] $items
     */
    public function __construct(BitrixOrder $order, Collection $items = null)
    {
        $this->order = $order;
        $this->orderId = $order->getId();
        $this->orderAccountNumber = $order->getAccountNumber();
        $this->price = $order->getPrice();
        $this->date = $order->getDate();
        $this->trackNumber = $order->getTrackNumber();

        $this->status = new OrderStatusViewModel($order->status);
        $this->isCanceled = $order->isCanceled();

        $properties = $order->getPropertyValues();

        $this->deliveryAddress = new AddressViewModel($properties, 'DELIVERY_');

        $this->pickupPoint = $properties['DELIVERY_PICKUP_POINT_ID'] ? PickupPoint::getById($properties['DELIVERY_PICKUP_POINT_ID']) : null;

        $this->userDescription = $order->getUserDescription();

        $this->items = $items ?? collect();
    }

    /**
     * Возвращает идентификатор заказа.
     * @return int
     */
    public function getId(): int
    {
        return $this->orderId;
    }

    /**
     * Возвращает номер заказа.
     * @return string
     */
    public function getAccountNumber(): string
    {
        return $this->orderAccountNumber;
    }

    /**
     * Возвращает номер заказа.
     * @return string
     */
    public function getNumber(): string
    {
        return $this->orderId;
    }

    /**
     * Возвращает дату и время заказа.
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Возвращает статус заказа.
     * @return OrderStatusViewModel
     */
    public function getStatus(): OrderStatusViewModel
    {
        return $this->status;
    }

    /**
     * Возвращает общую стоимость заказа.
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Возвращает адрес доставки.
     * @return AddressViewModel
     */
    public function getDeliveryAddress(): AddressViewModel
    {
        return $this->deliveryAddress;
    }

    /**
     * Возвращает трек номер доставки
     * @return string
     */
    public function getTrackNumber(): string
    {
        return $this->trackNumber;
    }

    /**
     * Возвращает URl для отслеживания доставки
     * @return string
     */
    public function getTrackUrl(): string
    {
        return 'https://www.cccb.ru/search/?q=' . $this->getTrackNumber();
    }

    /**
     * Возвращает пункт самовывоза.
     * @return PickupPoint|null
     */
    public function getPickupPoint(): ?PickupPoint
    {
        return $this->pickupPoint;
    }

    /**
     * @return bool
     */
    public function isDelivery(): bool
    {
        return $this->isDelivery;
    }

    /**
     * @return string
     */
    public function getCccbPickpointAddress(): string
    {
        return $this->cccbPickpointAddress;
    }

    /**
     * Возвращает позиции заказа.
     * @return Collection|OrderItemViewModel[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * Возвращает отформатированную цену.
     * @return string
     */
    public function formatPrice(Currency $currency = null): string
    {
        return NumberHelper::transformNumberToPrice($this->getPrice($currency));
    }

    /**
     * Возвращает только ювелирные изделия.
     * @return Collection|OrderItemViewModel[]
     */
    public function getJewelries(): Collection
    {
        return $this->items->filter(function (OrderItemViewModel $item) {
            return $item->isJewelry();
        });
    }

    /**
     * Возвращает только бриллианты.
     * @return Collection|OrderItemViewModel[]
     */
    public function getDiamonds(): Collection
    {
        return $this->items->filter(function (OrderItemViewModel $item) {
            return $item->isDiamond();
        });
    }

    /**
     * Возвращает коллекцию заполненных моделей для заказов по их идентификаторам.
     * @param array $ids - массив идентификаторов заказов.
     * @return Collection|OrderViewModel[]
     */
    public static function fromOrderIds(array $ids): Collection
    {
        $orders = BitrixOrder::filter(['ID' => $ids])
            ->with('properties', 'status')
            ->getList();

        return self::fromOrders($orders);
    }

    /**
     * Возвращает коллекцию заполненных моделей для заданных заказов.
     * @param Collection|BitrixOrder[] $orders - заказы, ключом коллекции должен выступать ID
     * @return Collection|OrderViewModel[]
     */
    public static function fromOrders(Collection $orders): Collection
    {
        $baskets = BitrixBasketItem::filter(['ORDER_ID' => $orders->keys()->all()])
            ->with('properties')
            ->getList();

        /** @var Collection|OrderItemViewModel[] $items */
        $items = collect($baskets);

        $items = $items->groupBy(function ($item) {
            return $item->getOrderId();
        });

        return $orders->map(function (BitrixOrder $order) use ($items) {
            return new OrderViewModel($order, $items[$order->getId()]);
        });
    }

    /**
     * @param BitrixOrder $order
     *
     * @return OrderViewModel
     */
    public static function fromOrder(BitrixOrder $order)
    {
        return self::fromOrders(collect()->put($order->getId(), $order))->first();
    }

    /**
     * Заказ отменен?
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this->isCanceled;
    }

    /**
     * Заказ завершен?
     *
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->getStatus()->isFinished();
    }

    /**
     * Получить объект заказа Битрикса
     * @return BitrixOrder
     */
    public function getOrder(): BitrixOrder
    {
        return $this->order;
    }

    /**
     * Возвращает дату в нужном формате и языке
     *
     * @return string
     *
     * @throws Exception
     */
    public function getTranslatedDate(): string
    {
        $date = (new PhpDateTime($this->getDate()));
        $dateResult = $date->format('d') . ' ' . DateTimeHelper::getMonthInNecessaryLanguage(
                $date,
                true,
                '',
                null,
                'F d'
            ) . ' ' . $date->format('Y');

        return $dateResult;
    }

    /**
     * Возвращает пользовательский комментарий к заказу
     * @return string
     */
    public function getUserDescription(): string
    {
        return (string)$this->userDescription;
    }
}

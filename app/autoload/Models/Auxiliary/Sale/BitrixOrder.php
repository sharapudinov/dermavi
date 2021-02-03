<?php
namespace App\Models\Auxiliary\Sale;

use App\Core\Sale\OrderMail\ProductOrderInterface;
use App\Core\SprintOptions\OrderSettings;
use App\Helpers\OrderHelper;
use App\Models\User;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Bitrix\Main\Type\DateTime;
use Bitrix\Sale\Internals\OrderTable;
use Illuminate\Support\Collection;
use App\Helpers\PriceHelper;
use App\Core\Currency\Currency;

/**
 * Модель для заказа Битрикс.
 * Class BitrixOrder
 * @package App\Models\Auxiliary\Sale
 *
 * @property Collection|BitrixBasketItem[] $items
 * @property Collection|BitrixOrderPropertyValue[] $properties
 * @property Collection|BitrixOrderPayment $payment
 * @property BitrixOrderStatus $status
 * @property User $user
 *
 * @method static BaseQuery forUser(int $userId)
 */
class BitrixOrder extends D7Model
{
    /** @var string Имя класса таблицы */
    const TABLE_CLASS = OrderTable::class;

    /**
     * Возвращает идентификатор заказа.
     * @return int
     */
    public function getId(): int
    {
        return (int)$this['ID'];
    }

    /**
     * Возвращает номер заказа.
     * @return string
     */
    public function getAccountNumber(): string
    {
        return (string)$this['ACCOUNT_NUMBER'];
    }

    /**
     * Возвращает общую стоимость заказа.
     * @return float
     */
    public function getPrice(): float
    {
        $currentCurrency = Currency::getCurrentCurrency();
        //fixme костыль, на случай когда товар отправляется в usd
        if ((Currency::USD_CURRENCY === $currentCurrency->getSymCode()) && (user() && user()->isAuthorized()))
        {
            return ceil((float)$this['PRICE']);
        }

        return ceil(PriceHelper::getPriceInSpecificCurrency((float)$this['PRICE'], $currentCurrency));
    }

    /**
     * Возвращает сумму заказа в рублях
     *
     * @return float
     */
    public function getPriceInRub(): float
    {
/** @todo Это неверно, заказ может быть оформлен в другой валюте */
        return $this['PRICE'];
    }

    /**
     * Валюта заказа
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return (string)$this['CURRENCY'];
    }

    /**
     * Возвращает стоимость доставки.
     * @return float
     */
    public function getPriceDelivery()
    {
        return (float)$this['PRICE_DELIVERY'];
    }

    /**
     * Возвращает оплаченую сумму.
     * @return float
     */
    public function getSumPaid()
    {
        return (float) $this['SUM_PAID'];
    }

    /**
     * Возвращает дату заказа.
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this['DATE_INSERT'];
    }

    /**
     * Возвращает дату и время изменения статуса заказа
     *
     * @return DateTime
     */
    public function getDateStatus(): DateTime
    {
        return $this['DATE_STATUS'];
    }

    /**
     * Возвращает код статуса заказа.
     * @return string
     */
    public function getStatusId(): string
    {
        return (string)$this['STATUS_ID'];
    }

    /**
     * Возвращает идентификатор пользователя, оформившего заказ.
     * @return int
     */
    public function getUserId(): int
    {
        return (int)$this['USER_ID'];
    }

    /**
     * Возвращает идентификатор службы доставки
     *
     * @return int|null
     */
    public function getDeliveryId(): ?int
    {
        return $this['DELIVERY_ID'];
    }

    /**
     * Возвращает запрос для связи с корзиной заказа.
     * @return BaseQuery
     */
    public function items(): BaseQuery
    {
        return $this->hasMany(BitrixBasketItem::class, 'ORDER_ID', 'ID');
    }

    /**
     * Возвращает запрос для получения модели пользователя, сделавшего заказ
     *
     * @return BaseQuery
     */
    public function user(): BaseQuery
    {
        return $this->hasOne(User::class, 'ID', 'USER_ID');
    }

    /**
     * Возвращает запрос для связи со свойствами заказа.
     * @return BaseQuery
     */
    public function properties(): BaseQuery
    {
        return $this->hasMany(BitrixOrderPropertyValue::class, 'ORDER_ID', 'ID');
    }

    /**
     * Массив значений свойств заказа по их коду
     * @return array
     */
    public function getPropertyValues()
    {
        return $this->properties->mapWithKeys(function (BitrixOrderPropertyValue $prop) {
                return [$prop->getCode() => $prop->getValue()];
            })->all();
    }

    /**
     * Возвращает запрос для связи со статусом заказа.
     * @return BaseQuery
     */
    public function status(): BaseQuery
    {
        return $this->hasOne(BitrixOrderStatus::class, 'ID', 'STATUS_ID');
    }

    /**
     * Возвращает запрос для связи с оплатой
     * @return BaseQuery
     */
    public function payment(): BaseQuery
    {
        return $this->hasOne(BitrixOrderPayment::class, 'ORDER_ID', 'ID');
    }

    /**
     * Отбор заказов по пользователю.
     * @param BaseQuery $query
     * @param int $userId
     * @return BaseQuery
     */
    public static function scopeForUser(BaseQuery $query, int $userId): BaseQuery
    {
        $query->filter['USER_ID'] = $userId;
        return $query;
    }

    /**
     * Заказ отменен?
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this['CANCELED'] == 'Y';
    }

    /**
     * Заказ оплачен?
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this['PAYED'] == 'Y';
    }

    /**
     * Заказ с доставкой?
     * @return bool
     */
    public function isDelivery(): bool
    {
        return $this->getDeliveryId() == OrderSettings::getCccbServiceId();
    }

    /**
     * Адрес пункта самовывоза спец связи
     * @return string
     */
    public function getCccbPickupAddress(): string
    {
        $propertyValues = $this->getPropertyValues();

        if ($this->isDelivery()) {
            return (string)$propertyValues['DELIVERY_PICKUP_POINT'];
        } else {
            return '';
        }
    }

    /**
     * Заказ с самовывозом?
     * @return bool
     */
    public function isPickup(): bool
    {
        return $this->getDeliveryId() == OrderSettings::getPickupServiceId();
    }

    /**
     * Возвращает трек номер доставки
     *
     * @return string
     */
    public function getTrackNumber(): string
    {
        return (string)$this['TRACKING_NUMBER'];
    }

    /**
     * Получить email пользователя (из заказа или из профиля)
     * @return mixed|string
     */
    public function getEmail()
    {
        $propertyValues = $this->getPropertyValues();
        $email = $propertyValues['EMAIL'];
        if (!$email) {
            $email = $this->user->getEmail();
        }

        return $email;
    }

    /**
     * Возвращает класс типа обработчика письма о заказе
     *
     * @return ProductOrderInterface
     */
    public function getMode(): ProductOrderInterface
    {
        return OrderHelper::getMode($this);
    }

    /**
     * Возвращает пользовательский комментарий к заказу
     * @return string
     */
    public function getUserDescription(): string
    {
        return (string)$this['USER_DESCRIPTION'];
    }
}

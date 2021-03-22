<?php
namespace App\Models\Auxiliary\Sale;

use App\Models\Catalog\Diamond;
use App\Models\Catalog\PaidService;
use App\Models\Catalog\ProductInterface;
use App\Models\Jewelry\JewelryConstructorReadyProduct;
use App\Models\Jewelry\JewelrySku;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Bitrix\Sale\Internals\BasketTable;
use Illuminate\Support\Collection;

/**
 * Класс-модель для элемента корзины.
 * Class BitrixOrderItem
 * @package App\Models\Auxiliary\Sale
 *
 * @property Collection|BitrixBasketItemProperty[] $properties
 * @property PaidService|null $service
 * @property Fuser|null $fuser
 */
class BitrixBasketItem extends D7Model
{
    /** @var string Имя класса таблицы */
    const TABLE_CLASS = BasketTable::class;

    /**
     * Возвращает идентификатор строки.
     * @return int
     */
    public function getId(): int
    {
        return (int) $this['ID'];
    }

    /**
     * Возвращает идентификатор основной строки набора.
     * @return int
     */
    public function getParentId(): int
    {
        return (int) $this['SET_PARENT_ID'];
    }

    /**
     * Возвращает идентификатор заказа.
     * @return int
     */
    public function getOrderId(): int
    {
        return (int) $this['ORDER_ID'];
    }

    /**
     * Получает наименование продукта
     *
     * @return string
     */
    public function getName(): string
    {
        return (string)$this['NAME'];
    }

    /**
     * Возвращает цену позиции.
     * @return float
     */
    public function getPrice(): float
    {
        return (float) $this['PRICE'];
    }

    /**
     * Возвращает идентификатор товара/услуги.
     * @return int
     */
    public function getProductId(): int
    {
        return (int) $this['PRODUCT_ID'];
    }

    /**
     * Возвращает количество товара.
     * @return int
     */
    public function getQuantity(): int
    {
        return (int) $this['QUANTITY'];
    }

    /**
     * Возвращает служебный код позиции.
     * @return string
     */
    public function getExternalId(): string
    {
        return (string) $this['PRODUCT_XML_ID'];
    }

    /**
     * Возвращает валюту товара
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return (string)$this['CURRENCY'];
    }

    /**
     * Возвращает запрос для получения связанных с моделью свойств.
     *
     * @internal
     * @return BaseQuery
     */
    public function properties(): BaseQuery
    {
        return $this->hasMany(BitrixBasketItemProperty::class, 'BASKET_ID', 'ID');
    }

    /**
     * Возвращает запрос для получения связанной услуги.
     *
     * @internal
     * @return BaseQuery
     */
    public function service(): BaseQuery
    {
        return $this->hasOne(PaidService::class, 'ID', 'PRODUCT_ID');
    }

    /**
     * Возвращает интерфейс продукта
     *
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        return null;
    }

    /**
     * Возвращает идентификатор сайта корзины
     *
     * @return string
     */
    public function getSiteId(): string
    {
        return (string)$this['LID'];
    }

    /**
     * Возвращает запрос для получения связанного fake user.
     *
     * @internal
     * @return BaseQuery
     */
    public function fuser(): BaseQuery
    {
        return $this->hasOne(Fuser::class, 'ID', 'FUSER_ID');
    }
}

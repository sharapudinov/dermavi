<?php
namespace App\Models\Auxiliary\Sale;

use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Bitrix\Sale\Internals\BasketPropertyTable;

/**
 * Класс-модель для значения свойства позиции заказа.
 * Class BitrixBasketItemProperty
 * @package App\Models\Auxiliary\Sale
 *
 * @method static BaseQuery forBasket(int $basketId)
 */
class BitrixBasketItemProperty extends D7Model
{
    /** @var string Имя класс таблицы */
    public const TABLE_CLASS = BasketPropertyTable::class;

    /**
     * Возвращает наименование свойства.
     * @return string
     */
    public function getName(): string
    {
        return (string) $this['NAME'];
    }

    /**
     * Возвращает код свойства.
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this['CODE'];
    }

    /**
     * Возвращает значение свойства.
     * @return string
     */
    public function getValue(): string
    {
        return (string) $this['VALUE'];
    }

    /**
     * Отбор свойств для конкретного элемента корзины.
     * @param BaseQuery $query
     * @param int $basketId
     * @return BaseQuery
     */
    public static function scopeForBasket(BaseQuery $query, int $basketId): BaseQuery
    {
        $query->filter['BASKET_ID'] = $basketId;
        return $query;
    }
}

<?php
namespace App\Models\Auxiliary\Sale;

use Arrilot\BitrixModels\Models\D7Model;
use Bitrix\Sale\Internals\OrderPropsValueTable;

/**
 * Класс-модель значения свойства заказа.
 * Class BitrixOrderPropertyValue
 * @package App\Models\Auxiliary\Sale
 */
class BitrixOrderPropertyValue extends D7Model
{
    /** @var string Имя класса таблицы */
    public const TABLE_CLASS = OrderPropsValueTable::class;

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
     * Возвращает наименование свойства.
     * @return string
     */
    public function getName(): string
    {
        return (string) $this['NAME'];
    }
}

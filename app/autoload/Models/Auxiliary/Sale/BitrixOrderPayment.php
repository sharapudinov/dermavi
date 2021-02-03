<?php

namespace App\Models\Auxiliary\Sale;

use Arrilot\BitrixModels\Models\D7Model;
use Bitrix\Sale\Internals\OrderTable;
use Bitrix\Sale\Internals\PaymentTable;

/**
 * Класс-модель, описывающий сущность "Оплата"
 * Class BitrixOrderPayment
 *
 * @package App\Models\Auxiliary\Sale
 */
class BitrixOrderPayment extends D7Model
{
    /** @var string Имя класса таблицы */
    const TABLE_CLASS = PaymentTable::class;

    /**
     * Возвращает название типа оплаты
     *
     * @return string
     */
    public function getName(): string
    {
        return $this['PAY_SYSTEM_NAME'];
    }

    /**
     * Возвращает идентификатор типа оплаты
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['PAY_SYSTEM_ID'];
    }

    /**
     * Возвращает флаг, указывающий на то, что заказ оплачен
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this['PAID'] == 'Y';
    }

    /**
     * Возвращает сумму платежа
     *
     * @return float
     */
    public function getSum(): float
    {
        return (float) $this['SUM'];
    }
}

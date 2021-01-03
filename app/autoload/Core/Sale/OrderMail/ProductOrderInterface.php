<?php

namespace App\Core\Sale\OrderMail;

/**
 * Интерфейс, описывающий методы получения данных для письма менеджеру о новом заказе
 * Interface ProductOrderInterface
 *
 * @package App\Core\Sale\OrderMail
 */
interface ProductOrderInterface
{
    /**
     * Возвращает тип товара
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Возвращает итоговую стоимость за все позиции текущего типа
     *
     * @return float
     */
    public function getCostPerType(): float;

    /**
     * Возвращает символьный код почтового события
     *
     * @return array|string[]
     */
    public function getMailEventName(): array;
}

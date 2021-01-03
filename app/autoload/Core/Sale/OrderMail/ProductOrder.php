<?php

namespace App\Core\Sale\OrderMail;

use App\Core\Sale\View\OrderViewModel;

/**
 * Класс, описывающий получение данных о заказе
 * Class ProductOrder
 *
 * @package App\Core\Sale\OrderMail
 */
class ProductOrder implements ProductOrderInterface
{
    /** @var OrderViewModel|null $order Модель заказа */
    private $order;

    /**
     * Записывает в объект модель заказа
     *Они
     * @param OrderViewModel $order Модель заказа
     *
     * @return static
     */
    public function setOrder(OrderViewModel $order): self
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Возвращает модель заказа
     *
     * @return OrderViewModel
     */
    protected function getOrder(): OrderViewModel
    {
        return $this->order;
    }

    /**
     * Возвращает тип товара
     *
     * @return string
     */
    public function getType(): string
    {
        return 'general';
    }

    /**
     * Возвращает итоговую стоимость за все позиции текущего типа
     *
     * @return float
     */
    public function getCostPerType(): float
    {
        return $this->order->getPrice();
    }

    /**
     * Возвращает символьный код почтового события
     *
     * @return array|string[]
     */
    public function getMailEventName(): array
    {
        return ['NEW_JEWELRY_ORDER_CREATE', 'NEW_ORDER_CREATE'];
    }
}

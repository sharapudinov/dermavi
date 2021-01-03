<?php

namespace App\Core\Sale\OrderMail;

/**
 * Класс, описывающий логику получения данных о бриллиантах в заказе
 * Class DiamondOrder
 *
 * @package App\Core\Sale\OrderMail
 */
class DiamondOrder extends ProductOrder implements ProductOrderInterface
{
    /**
     * Возвращает тип товара
     *
     * @return string
     */
    public function getType(): string
    {
        return 'diamonds';
    }

    /**
     * Возвращает итоговую стоимость за все позиции текущего типа
     *
     * @return float
     */
    public function getCostPerType(): float
    {
        $cost = $this->getOrder()->getOrder()->getPriceDelivery() ?? 0;
        foreach ($this->getOrder()->getItems() as $item) {
            if ($item->isDiamond() || $item->isService()) {
                $cost += $item->getPrice();
                foreach ($item->getAttachedServices() as $service) {
                    $cost += $service->getPrice();
                }
            }
        }

        return $cost;
    }

    /**
     * Возвращает символьный код почтового события
     *
     * @return array|string[]
     */
    public function getMailEventName(): array
    {
        return ['NEW_ORDER_CREATE'];
    }
}

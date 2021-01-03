<?php

namespace App\EventHandlers;

use App\Models\HL\DiamondOrder;
use CEvent;

/**
 * Класс-обработчик событий в личном кабинете
 * Class PersonalSectionHandlers
 * @package App\EventHandlers
 */
class PersonalSectionHandlers
{
    /**
     * Отправляет менеджеру письмо о новом заказе на бриллиант
     *
     * @param DiamondOrder $order - Заказ на новый бриллиант
     * @return void
     */
    public static function newDiamondOrder(DiamondOrder $order): void
    {
        CEvent::SendImmediate('DIAMOND_ORDER_MANAGER', 's1', [
            'ORDER_ID' => $order->getId(),
        ], 'Y', '', $order->getFiles(), 'ru');
    }
}

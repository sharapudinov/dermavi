<?php

namespace App\Helpers;

/**
 * Класс, описывающий статусы заказа
 * Class BitrixOrderStatusConstants
 *
 * @package App\Helpers
 */
class BitrixOrderStatusConstants
{
    /** @var string Заказ - Отменен */
    public const CANCELED = 'C';

    /** @var string Заказ - Отправка */
    public const DELIVERY = 'D';

    /** @var string Заказ - Завершен */
    public const FINISHED = 'F';

    /** @var string Заказ - В обработке */
    public const PROCESSING = 'N';

    /** @var string Отгрузка - На доставке */
    public const SHIPMENT_DELIVERY = 'DF';

    /** @var string Отгрузка - Ожидает обработки */
    public const SHIPMENT_PROCESSING = 'DN';
}

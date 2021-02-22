<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;

Loader::includeModule('sale');

/**
 * Class RenameOrderStatuses20190416125338401604
 */
class RenameOrderStatuses20190416125338401604 extends BitrixMigration
{
    /**
     * Новые названия
     */
    const NEW_NAMES = [
        'N' => [
            [
                'LID' => 'ru',
                'NAME' => 'В обработке',
                'DESCRIPTION' => 'Заказ принят, но пока не обрабатывается (например, заказ только что создан или ожидается оплата заказа)',
            ],
            [
                'LID' => 'en',
                'NAME' => 'In processing',
                'DESCRIPTION' => 'The order is accepted, but is not processed yet (for example, the order has just been created or the order is expected to be paid)',
            ],
            [
                'LID' => 'cn',
                'NAME' => '在处理中',
                'DESCRIPTION' => '订单已被接受，但尚未处理（例如，订单刚刚创建或订单预计已支付）',
            ],
        ],
        'DF' => [
            [
                'LID' => 'ru',
                'NAME' => 'Отгружен',
                'DESCRIPTION' => 'Отгружен',
            ],
            [
                'LID' => 'en',
                'NAME' => 'Shipped',
                'DESCRIPTION' => 'Shipped',
            ],
            [
                'LID' => 'cn',
                'NAME' => '运',
                'DESCRIPTION' => '运',
            ],
        ],
        'DN' => [
            [
                'LID' => 'ru',
                'NAME' => 'Ожидает обработки',
                'DESCRIPTION' => 'Ожидает обработки',
            ],
            [
                'LID' => 'en',
                'NAME' => 'Waiting to be processed',
                'DESCRIPTION' => 'Waiting to be processed',
            ],
            [
                'LID' => 'cn',
                'NAME' => '等待处理',
                'DESCRIPTION' => '等待处理',
            ],
        ],
        'F' => [
            [
                'LID' => 'ru',
                'NAME' => 'Завершён',
                'DESCRIPTION' => 'Заказ доставлен и оплачен',
            ],
            [
                'LID' => 'en',
                'NAME' => 'Completed',
                'DESCRIPTION' => 'Order delivered and paid',
            ],
            [
                'LID' => 'cn',
                'NAME' => '完成',
                'DESCRIPTION' => '订单交付和付款',
            ],
        ],
    ];
    
    /**
     * Старые названия
     */
    const OLD_NAMES = [
        'DF' => [
            [
                'LID' => 'ru',
                'NAME' => 'Отгружен',
                'DESCRIPTION' => 'Отгружен',
            ],
            [
                'LID' => 'en',
                'NAME' => 'Отгружен',
                'DESCRIPTION' => 'Отгружен',
            ],
            [
                'LID' => 'cn',
                'NAME' => 'Отгружен',
                'DESCRIPTION' => 'Отгружен',
            ],
        ],
        'DN' => [
            [
                'LID' => 'ru',
                'NAME' => 'Ожидает обработки',
                'DESCRIPTION' => 'Ожидает обработки',
            ],
            [
                'LID' => 'en',
                'NAME' => 'Ожидает обработки',
                'DESCRIPTION' => 'Ожидает обработки',
            ],
            [
                'LID' => 'cn',
                'NAME' => 'Ожидает обработки',
                'DESCRIPTION' => 'Ожидает обработки',
            ],
        ],
        'F' => [
            [
                'LID' => 'ru',
                'NAME' => 'Выполнен',
                'DESCRIPTION' => 'Заказ доставлен и оплачен',
            ],
            [
                'LID' => 'en',
                'NAME' => 'Выполнен',
                'DESCRIPTION' => 'Заказ доставлен и оплачен',
            ],
            [
                'LID' => 'cn',
                'NAME' => 'Выполнен',
                'DESCRIPTION' => 'Заказ доставлен и оплачен',
            ],
        ],
        'N' => [
            [
                'LID' => 'ru',
                'NAME' => 'Принят, ожидается оплата',
                'DESCRIPTION' => 'Заказ принят, но пока не обрабатывается (например, заказ только что создан или ожидается оплата заказа)',
            ],
            [
                'LID' => 'en',
                'NAME' => 'Принят, ожидается оплата',
                'DESCRIPTION' => 'Заказ принят, но пока не обрабатывается (например, заказ только что создан или ожидается оплата заказа)',
            ],
            [
                'LID' => 'cn',
                'NAME' => 'Принят, ожидается оплата',
                'DESCRIPTION' => 'Заказ принят, но пока не обрабатывается (например, заказ только что создан или ожидается оплата заказа)',
            ],
        ],
    ];
    
    /**
     * Run the migration
     */
    public function up()
    {
        foreach (static::NEW_NAMES as $statusId => $names) {
            $status = new \CSaleStatus();
            $status->Update($statusId, [
                'ID' => $statusId,
                'LANG' => $names
            ]);
        }
    }

    /**
     * Reverse the migration
     */
    public function down()
    {
        foreach (static::OLD_NAMES as $statusId => $names) {
            $status = new \CSaleStatus();
            $status->Update($statusId, [
                'ID' => $statusId,
                'LANG' => $names
            ]);
        }
    }
}

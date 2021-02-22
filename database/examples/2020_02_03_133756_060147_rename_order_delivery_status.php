<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;

Loader::includeModule('sale');

class RenameOrderDeliveryStatus20200203133756060147 extends BitrixMigration
{
    /**
     * Новые названия
     */
    const NEW_NAMES
        = [

            'DF' => [
                [
                    'LID'         => 'ru',
                    'NAME'        => 'На доставке',
                    'DESCRIPTION' => 'На доставке',
                ],
                [
                    'LID'         => 'en',
                    'NAME'        => 'Shipped',
                    'DESCRIPTION' => 'Shipped',
                ],
                [
                    'LID'         => 'cn',
                    'NAME'        => '交貨',
                    'DESCRIPTION' => '交貨',
                ],
            ],

        ];

    /**
     * Старые названия
     */
    const OLD_NAMES
        = [
            'DF' => [
                [
                    'LID'         => 'ru',
                    'NAME'        => 'Отгружен',
                    'DESCRIPTION' => 'Отгружен',
                ],
                [
                    'LID'         => 'en',
                    'NAME'        => 'Shipped',
                    'DESCRIPTION' => 'Shipped',
                ],
                [
                    'LID'         => 'cn',
                    'NAME'        => '运',
                    'DESCRIPTION' => '运',
                ],
            ],
        ];


    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        foreach (static::NEW_NAMES as $statusId => $names) {
            $status = new \CSaleStatus();
            $status->Update($statusId, [
                'ID'   => $statusId,
                'LANG' => $names
            ]);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        foreach (static::OLD_NAMES as $statusId => $names) {
            $status = new \CSaleStatus();
            $status->Update($statusId, [
                'ID'   => $statusId,
                'LANG' => $names
            ]);
        }
    }
}

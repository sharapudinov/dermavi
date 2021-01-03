<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Sale\Internals\StatusTable;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Main\Loader;

class AddCancelStatusToOrder20191127150133830511 extends BitrixMigration
{
    /**
     * @var bool
     */
    public $use_transaction = true;

    /**
     * AddDeliveryOrderStatus20190425103612535865 constructor.
     * @throws Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        Loader::includeModule('sale');
    }

    /**
     * Run the migration.
     * @throws MigrationException
     */
    public function up()
    {
        $result = StatusTable::add([
            'ID' => 'C',
            'TYPE' => 'O',
            'SORT' => 120,
            'NOTIFY' => 'Y',
            'COLOR' => 'Y',
        ]);
        if (!$result->isSuccess()) {
            throw new MigrationException('Не удалось создать новый статус заказа "Отменен"');
        }

        $statusId = $result->getId();
        $statusLangs = [
            [
                'STATUS_ID' => $statusId,
                'LID' => 'ru',
                'NAME' => 'Отменен',
            ],
            [
                'STATUS_ID' => $statusId,
                'LID' => 'en',
                'NAME' => 'Canceled',
            ],
            [
                'STATUS_ID' => $statusId,
                'LID' => 'cn',
                'NAME' => '已取消',
            ],
        ];
        foreach ($statusLangs as $statusLang) {
            $result = StatusLangTable::add($statusLang);
            if (!$result->isSuccess()) {
                throw new MigrationException('Не удалось добавить языковые поля для статуса заказа "Отменен" для языка "' . $statusLang['LID'] .'"');
            }
        }
    }

    /**
     * Reverse the migration.
     * @throws MigrationException
     */
    public function down()
    {
        StatusLangTable::deleteByStatus('С');
        $result = StatusTable::delete('С');
        if (!$result->isSuccess()) {
            throw new MigrationException('Не удалось удалить статус заказа "Отменен"');
        }
    }
}


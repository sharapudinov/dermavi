<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Sale\Internals\StatusTable;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Main\Loader;

/**
 * Class AddDeliveryOrderStatus20190425103612535865
 */
class AddDeliveryOrderStatus20190425103612535865 extends BitrixMigration
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
            'ID' => 'D',
            'TYPE' => 'O',
            'SORT' => 110,
            'NOTIFY' => 'Y',
            'COLOR' => 'Y',
        ]);
        if (!$result->isSuccess()) {
            throw new MigrationException('Не удалось создать новый статус заказа "Отправка"');
        }
        
        $statusId = $result->getId();
        $statusLangs = [
            [
                'STATUS_ID' => $statusId,
                'LID' => 'ru',
                'NAME' => 'Отправка',
            ],
            [
                'STATUS_ID' => $statusId,
                'LID' => 'en',
                'NAME' => 'Delivery',
            ],
            [
                'STATUS_ID' => $statusId,
                'LID' => 'cn',
                'NAME' => '交货',
            ],
        ];
        foreach ($statusLangs as $statusLang) {
            $result = StatusLangTable::add($statusLang);
            if (!$result->isSuccess()) {
                throw new MigrationException('Не удалось добавить языковые поля для статуса заказа "Отправка" для языка "' . $statusLang['LID'] .'"');
            }
        }
    }

    /**
     * Reverse the migration.
     * @throws MigrationException
     */
    public function down()
    {
        StatusLangTable::deleteByStatus('D');
        $result = StatusTable::delete('D');
        if (!$result->isSuccess()) {
            throw new MigrationException('Не удалось удалить статус заказа "Отправка"');
        }
    }
}

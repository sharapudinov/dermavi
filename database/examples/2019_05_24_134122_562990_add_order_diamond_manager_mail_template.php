<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, реализующий миграцию для добавления почтового шаблона "Бриллиант на заказ - менеджер"
 * Class AddOrderDiamondManagerMailTemplate20190524134122562990
 */
class AddOrderDiamondManagerMailTemplate20190524134122562990 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        // $resultType = EventTypeTable::add([
        //     'fields' => [
        //         'LID' => 'ru',
        //         'EVENT_NAME' => 'DIAMOND_ORDER_MANAGER',
        //         'NAME' => 'Бриллиант на заказ - менеджер',
        //         'DESCRIPTION' => "#ORDER_ID# - Номер заказа"
        //     ]
        // ]);
        // if (!$resultType->isSuccess()) {
        //     throw new MigrationException('Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages()));
        // }

        // $resultMessage = EventMessageTable::add([
        //     'fields' => [
        //         'EVENT_NAME' => 'DIAMOND_ORDER_MANAGER',
        //         'LID' => 's1',
        //         'LANGUAGE_ID' => 'ru',
        //         'SITE_TEMPLATE_ID' => '',
        //         'ACTIVE' => 'Y',
        //         'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
        //         'EMAIL_TO' => 'spirin@greensight.ru',
        //         'CC' => '',
        //         'SUBJECT' => 'Бриллиант на заказ',
        //         'BODY_TYPE' => 'html',
        //         'MESSAGE' => ""
        //     ],
        // ]);

        // EventMessageSiteTable::add([
        //     'fields' => [
        //         'EVENT_MESSAGE_ID' => $resultMessage->getId(),
        //         'SITE_ID' => 's1',
        //     ],
        // ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        // $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'DIAMOND_ORDER_MANAGER']]);
        // while ($type = $res->fetch()) {
        //     EventTypeTable::delete($type["ID"]);
        // }

        // $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'DIAMOND_ORDER_MANAGER']]);
        // while ($type = $res->fetch()) {
        //     $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
        //     db()->query('DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]);

        //     EventMessageTable::delete($type["ID"]);
        // }
    }
}

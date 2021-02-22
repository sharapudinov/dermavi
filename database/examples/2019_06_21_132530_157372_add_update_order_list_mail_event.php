<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;


class AddUpdateOrderListMailEvent20190621132530157372 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $resultType = EventTypeTable::add([
            'fields' => [
                'LID' => 'ru',
                'EVENT_NAME' => 'ORDER_LIST_UPDATE_MANAGER',
                'NAME' => 'Обновлен состав заказа - менеджер',
                'DESCRIPTION' => "#ORDER_ID# - Номер заказа\n" .
                    "#ORDER_DATE# - Дата заказа\n" .
                    "#USER_NAME# - заказчик\n" .
                    "#PRICE# - сумма заказа\n" .
                    "#EMAIL_TO# - E-Mail заказчика\n" .
                    "#REQUEST_URL# - Ссылка на заказ"
            ]
        ]);
        if (!$resultType->isSuccess()) {
            throw new MigrationException('Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages()));
        }

        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => 'ORDER_LIST_UPDATE_MANAGER',
                'LID' => 's2',
                'LANGUAGE_ID' => 'ru',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => 'spirin@greensight.ru',
                'CC' => '',
                'SUBJECT' => 'АЛРОСА: обновлен состав зааза',
                'BODY_TYPE' => 'html',
                'MESSAGE' => "<h2>Обновлен состав заказа ##ORDER_ID#</h2>
                    <p>Имя заказчика: #USER_NAME#</p>
                    <p>Email: #EMAIL_TO#</p>
                    <p>Ссылка на заказ: #REQUEST_URL#</p>"
            ],
        ]);

        EventMessageSiteTable::add([
            'fields' => [
                'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                'SITE_ID' => 's2',
            ],
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'ORDER_LIST_UPDATE_MANAGER']]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'ORDER_LIST_UPDATE_MANAGER']]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
            db()->query('DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]);

            EventMessageTable::delete($type["ID"]);
        }

    }
}

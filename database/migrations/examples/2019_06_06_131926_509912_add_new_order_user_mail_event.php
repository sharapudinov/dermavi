<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class AddNewOrderUserMailEvent20190606131926509912 extends BitrixMigration
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
                 'LID' => 'en',
                 'EVENT_NAME' => 'NEW_ORDER_CREATE_USER',
                 'NAME' => 'Новый заказ - пользователь',
                 'DESCRIPTION' => "#ORDER_ID# - Номер заказа\n" .
                     "#SITE_URL# - Ссылка на сайт\n" .
                     "#ORDER_DATE# - Дата заказа\n" .
                     "#ORDER_COST# - Стоимость заказа\n" .
                     "#EMAIL_TO# - Email пользователя\n" .
                     "#USER_NAME# - Имя пользователя"
             ]
         ]);
         if (!$resultType->isSuccess()) {
             throw new MigrationException('Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages()));
         }

         $resultMessage = EventMessageTable::add([
             'fields' => [
                 'EVENT_NAME' => 'NEW_ORDER_CREATE_USER',
                 'LID' => 's1',
                 'LANGUAGE_ID' => 'en',
                 'SITE_TEMPLATE_ID' => '',
                 'ACTIVE' => 'Y',
                 'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                 'EMAIL_TO' => '#EMAIL_TO#',
                 'CC' => '',
                 'SUBJECT' => 'Alrosa: new order',
                 'BODY_TYPE' => 'html',
                 'MESSAGE' => "<p>Hello, #USER_NAME#.</p>" .
                     "<p>Your order №#ORDER_ID# had been accepted</p>" .
                     "<p>Order date: #ORDER_DATE#</p>" .
                     "<p>Order cost: #ORDER_COST#</p>" .
                     "<p>We have started processing your order, your manager will contact you soon.</p>"
             ],
         ]);

         EventMessageSiteTable::add([
             'fields' => [
                 'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                 'SITE_ID' => 's1',
             ],
         ]);

        $resultType = EventTypeTable::add([
            'fields' => [
                'LID' => 'ru',
                'EVENT_NAME' => 'NEW_ORDER_CREATE_USER',
                'NAME' => 'Новый заказ - пользователь',
                'DESCRIPTION' => "#ORDER_ID# - Номер заказа\n" .
                    "#SITE_URL# - Ссылка на сайт\n" .
                    "#ORDER_DATE# - Дата заказа\n" .
                    "#ORDER_COST# - Стоимость заказа\n" .
                    "#EMAIL_TO# - Email пользователя\n" .
                    "#USER_NAME# - Имя пользователя"
            ]
        ]);
        if (!$resultType->isSuccess()) {
            throw new MigrationException('Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages()));
        }

        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => 'NEW_ORDER_CREATE_USER',
                'LID' => 's2',
                'LANGUAGE_ID' => 'ru',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'CC' => '',
                'SUBJECT' => 'АЛРОСА: новый заказ',
                'BODY_TYPE' => 'html',
                'MESSAGE' => "<p>Здравствуйте, #USER_NAME#.</p>" .
                    "<p>Ваш заказ №#ORDER_ID# на сайте #SITE_URL# успешно создан</p>" .
                    "<p>Дата заказа: #ORDER_DATE#</p>" .
                    "<p>Итоговая стоимость: #ORDER_COST#</p>" .
                    "<p>Мы приступили к обработке вашего заказа, скоро с вами свяжется ваш менеджер.</p>"
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
         $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'NEW_ORDER_CREATE_USER']]);
         while ($type = $res->fetch()) {
             EventTypeTable::delete($type["ID"]);
         }

         $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'NEW_ORDER_CREATE_USER']]);
         while ($type = $res->fetch()) {
             $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
             db()->query('DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]);

             EventMessageTable::delete($type["ID"]);
         }
    }
}

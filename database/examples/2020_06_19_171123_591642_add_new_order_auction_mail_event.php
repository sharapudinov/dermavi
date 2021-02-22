<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, описывающий миграцию для добавления почтового события о заказе аукционных товаров
 * Class AddNewOrderAuctionMailEvent20200619171123591642
 */
class AddNewOrderAuctionMailEvent20200619171123591642 extends BitrixMigration
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
                'EVENT_NAME' => 'NEW_AUCTION_ORDER_CREATE',
                'NAME' => 'Новый заказ аукционных бриллиантов',
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
                'EVENT_NAME' => 'NEW_AUCTION_ORDER_CREATE',
                'LID' => 's1',
                'LANGUAGE_ID' => 'en',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => 'polished-auction@alrosa.ru',
                'CC' => '',
                'SUBJECT' => 'Алроса: новый заказ',
                'BODY_TYPE' => 'html',
                'MESSAGE' => "<?php 
EventMessageThemeCompiler::includeComponent(
    'email.dispatch:order.manager', 
    '', 
    [
        'order_id' => #ORDER_ID#,
        'update_text' => #UPDATE_TEXT#,
        'mode' => #MODE#
    ]);
?>"
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
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'NEW_AUCTION_ORDER_CREATE']]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'NEW_AUCTION_ORDER_CREATE']]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
            db()->query('DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]);

            EventMessageTable::delete($type["ID"]);
        }
    }
}

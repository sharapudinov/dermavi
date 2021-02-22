<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class AddChinaNewOrderTemplate20200114142539103636 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {

        //@TODO Перевод на китайский
        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME'       => 'NEW_ORDER_CREATE_USER',
                'LID'              => 's3',
                'LANGUAGE_ID'      => 'en',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE'           => 'Y',
                'EMAIL_FROM'       => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO'         => '#EMAIL_TO#',
                'CC'               => '',
                'SUBJECT'   => 'АЛРОСА: новый заказ',
                'BODY_TYPE' => 'html',
                'MESSAGE'   => "<p>Здравствуйте, #USER_NAME#.</p>".
                    "<p>Ваш заказ №#ORDER_ID# на сайте #SITE_URL# успешно создан</p>".
                    "<p>Дата заказа: #ORDER_DATE#</p>".
                    "<p>Итоговая стоимость: #ORDER_COST#</p>".
                    "<p>Мы приступили к обработке вашего заказа, скоро с вами свяжется ваш менеджер.</p>"
            ],
        ]);

        EventMessageSiteTable::add([
            'fields' => [
                'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                'SITE_ID'          => 's3',
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
        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'NEW_ORDER_CREATE_USER','LID'=>'s3']]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
            db()->query('DELETE FROM `'.EventMessageSiteTable::getTableName().'` WHERE `EVENT_MESSAGE_ID` = '.$type["ID"]);

            EventMessageTable::delete($type["ID"]);
        }
    }
}

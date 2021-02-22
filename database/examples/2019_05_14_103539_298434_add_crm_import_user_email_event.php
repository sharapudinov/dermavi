<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class AddCrmImportUserEmailEvent20190514103539298434 extends BitrixMigration
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
                'EVENT_NAME' => 'IMPORT_USER_FROM_CRM',
                'NAME' => 'Пользователь импортирован из crm',
                'DESCRIPTION' => "#EMAIL_TO# - Email пользователя\n" .
                    "#PASSWORD# - Пароль пользователя\n" .
                    "#SITE_URL# - Ссылка на сайт"
            ]
        ]);
        if (!$resultType->isSuccess()) {
            throw new MigrationException('Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages()));
        }

        $resultType = EventTypeTable::add([
            'fields' => [
                'LID' => 'en',
                'EVENT_NAME' => 'IMPORT_USER_FROM_CRM',
                'NAME' => 'Пользователь импортирован из crm',
                'DESCRIPTION' => "#EMAIL_TO# - Email пользователя\n" .
                    "#PASSWORD# - Пароль пользователя\n" .
                    "#SITE_URL# - Ссылка на сайт"
            ]
        ]);
        if (!$resultType->isSuccess()) {
            throw new MigrationException('Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages()));
        }

        $resultType = EventTypeTable::add([
            'fields' => [
                'LID' => 'cn',
                'EVENT_NAME' => 'IMPORT_USER_FROM_CRM',
                'NAME' => 'Пользователь импортирован из crm',
                'DESCRIPTION' => "#EMAIL_TO# - Email пользователя\n" .
                    "#PASSWORD# - Пароль пользователя\n" .
                    "#SITE_URL# - Ссылка на сайт"
            ]
        ]);
        if (!$resultType->isSuccess()) {
            throw new MigrationException('Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages()));
        }

        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => 'IMPORT_USER_FROM_CRM',
                'LID' => 's1',
                'LANGUAGE_ID' => 'en',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'CC' => '',
                'SUBJECT' => 'You had been added in database of #SITE_URL#',
                'BODY_TYPE' => 'html',
                'MESSAGE' =>
                    "<p>Your login: #EMAIL_TO#</p>\n" .
                    "<p>Your password: #PASSWORD#</p>\n" .
                    "<p><a href='#SITE_URL#' target='_blank'>#SITE_URL#</a></p>\n"
            ],
        ]);

        EventMessageSiteTable::add([
            'fields' => [
                'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                'SITE_ID' => 's1',
            ],
        ]);

        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => 'IMPORT_USER_FROM_CRM',
                'LID' => 's1',
                'LANGUAGE_ID' => 'ru',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'CC' => '',
                'SUBJECT' => 'Вы были внесены в базу сайта #SITE_URL#',
                'BODY_TYPE' => 'html',
                'MESSAGE' =>
                    "<p>Ваш логин: #EMAIL_TO#</p>\n" .
                    "<p>Ваш пароль: #PASSWORD#</p>\n" .
                    "<p><a href='#SITE_URL#' target='_blank'>#SITE_URL#</a></p>\n"
            ],
        ]);

        EventMessageSiteTable::add([
            'fields' => [
                'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                'SITE_ID' => 's2',
            ],
        ]);

        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => 'IMPORT_USER_FROM_CRM',
                'LID' => 's1',
                'LANGUAGE_ID' => 'cn',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'CC' => '',
                'SUBJECT' => '您已添加到站点数据库 #SITE_URL#',
                'BODY_TYPE' => 'html',
                'MESSAGE' =>
                    "<p>你的登录: #EMAIL_TO#</p>\n" .
                    "<p>你的密码: #PASSWORD#</p>\n" .
                    "<p><a href='#SITE_URL#' target='_blank'>#SITE_URL#</a></p>\n"
            ],
        ]);

        EventMessageSiteTable::add([
            'fields' => [
                'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                'SITE_ID' => 's3',
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
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'IMPORT_USER_FROM_CRM']]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'IMPORT_USER_FROM_CRM']]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
            db()->query('DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]);

            EventMessageTable::delete($type["ID"]);
        }
    }
}

<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class AddMailEventForNotImportedUsersAlert20190515103216812820 extends BitrixMigration
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
                'EVENT_NAME' => 'IMPORT_USER_FROM_CRM_FAILED_ALERT',
                'NAME' => 'Неимпортированные из crm пользователи',
                'DESCRIPTION' => "#EMAIL_TO# - Email пользователя\n" .
                    "#USERS# - Список пользователей и причина неудачного импорота\n"
            ]
        ]);
        if (!$resultType->isSuccess()) {
            throw new MigrationException('Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages()));
        }

        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => 'IMPORT_USER_FROM_CRM_FAILED_ALERT',
                'LID' => 's1',
                'LANGUAGE_ID' => 'ru',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'CC' => '',
                'SUBJECT' => 'Неудачный импорт',
                'BODY_TYPE' => 'html',
                'MESSAGE' =>
                    "<p>Список неудачно импортированных пользователей из CRM:</p>\n" .
                    "<p>>#USERS#</p>\n"
            ],
        ]);

        EventMessageSiteTable::add([
            'fields' => [
                'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                'SITE_ID' => 's1',
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
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'IMPORT_USER_FROM_CRM_FAILED_ALERT']]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'IMPORT_USER_FROM_CRM_FAILED_ALERT']]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
            db()->query('DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]);

            EventMessageTable::delete($type["ID"]);
        }
    }
}

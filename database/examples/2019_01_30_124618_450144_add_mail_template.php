<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class AddMailTemplate20190130124618450144 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        // copy this migration

        $resultType = EventTypeTable::add([
            'fields' => [
                'LID' => 'ru',
                'EVENT_NAME' => 'VIEWING_REQUEST',
                'NAME' => 'Запросить показ',
                'DESCRIPTION' => "#UF_URL_DIAMOND# - Ссылка на бриллиант\n" .
                "#UF_DATE_OF_VIEWING# - Дата показа\n" .
                "#UF_COMMENT# - Комментарий\n" .
                "#UF_TIME_OF_VIEWING# - Время показа\n" .
                "#UF_TAX_ID# - ИНН\n" .
                "#UF_COMPANY_ACTIVITY# - Сфера деятельности компании\n" .
                "#UF_COUNTRY# - Страна\n" .
                "#UF_COMPANY_NAME# - Название компании\n" .
                "#UF_PHONE# - Телефон\n" .
                "#UF_EMAIL# - Email\n" .
                "#UF_SURNAME# - Фамилия\n" .
                "#UF_NAME# - Имя\n",
            ],
        ]);
        if (!$resultType->isSuccess()) {
            throw new MigrationException('Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages()));
        }

        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => 'VIEWING_REQUEST',
                'LID' => 's1',
                'LANGUAGE_ID' => 'ru',
                'SITE_TEMPLATE_ID' => 'email_dispatch',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => 'diamond@alrosa.ru',
                'CC' => 'TkachevaAM@alrosa.ru',
                'SUBJECT' => 'Запрос на просмотр бриллианта',
                'BODY_TYPE' => 'html',
                'MESSAGE' =>
                "Ссылка на бриллиант - #UF_URL_DIAMOND# </br>\n" .
                "Дата показа - #UF_DATE_OF_VIEWING# </br>\n" .
                "Комментарий - #UF_COMMENT# </br>\n" .
                "Время показа - #UF_TIME_OF_VIEWING# </br>\n" .
                "ИНН - #UF_TAX_ID# </br>\n" .
                "Сфера деятельности компании - #UF_COMPANY_ACTIVITY# </br>\n" .
                "Страна - #UF_COUNTRY# </br>\n" .
                "Название компании - #UF_COMPANY_NAME# </br>\n" .
                "Телефон - #UF_PHONE# </br>\n" .
                "Email - #UF_EMAIL# </br>\n" .
                "Фамилия - #UF_SURNAME# </br>\n" .
                "Имя - #UF_NAME </br>\n",
            ],
        ]);

        if (!$resultMessage->isSuccess()) {
            return;
        }

        $listSite = ['s1', 's2', 's3'];
        $id = $resultMessage->getId();
        foreach ($listSite as $siteID) {
            EventMessageSiteTable::add([
                'fields' => [
                    'EVENT_MESSAGE_ID' => $id,
                    'SITE_ID' => $siteID,
                ],
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
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'VIEWING_REQUEST']]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'VIEWING_REQUEST']]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
            db()->query('DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]);

            EventMessageTable::delete($type["ID"]);
        }
    }
}

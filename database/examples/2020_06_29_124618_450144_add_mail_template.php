<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Class AddMailTemplate20200629124618450144
 */
class AddMailTemplate20200629124618450144 extends BitrixMigration
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
                'EVENT_NAME' => 'VIEWING_REQUEST_PB',
                'NAME' => 'Добавлена заявка через форму обратной связи',
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
                "#UF_NAME# - Имя\n" .
                "#EMAIL_TO# - Email менеджера",
            ],
        ]);

        if (!$resultType->isSuccess()) {
            throw new MigrationException('Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages()));
        }

        $resultType = EventTypeTable::add([
            'fields' => [
                'LID' => 'en',
                'EVENT_NAME' => 'VIEWING_REQUEST_PB',
                'NAME' => 'New feedback form request',
                'DESCRIPTION' => "#UF_URL_DIAMOND# - Diamond link\n" .
                                 "#UF_DATE_OF_VIEWING# - Date of viewing\n" .
                                 "#UF_COMMENT# - Comment\n" .
                                 "#UF_TIME_OF_VIEWING# - Time of viewing\n" .
                                 "#UF_TAX_ID# - INN\n" .
                                 "#UF_COMPANY_ACTIVITY# - Company activity\n" .
                                 "#UF_COUNTRY# - Country\n" .
                                 "#UF_COMPANY_NAME# - Company name\n" .
                                 "#UF_PHONE# - Phone\n" .
                                 "#UF_EMAIL# - Email\n" .
                                 "#UF_SURNAME# - Last name\n" .
                                 "#UF_NAME# - First name\n" .
                                 "#EMAIL_TO# - Manager email",
            ],
        ]);

        if (!$resultType->isSuccess()) {
            throw new MigrationException('Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages()));
        }

        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => 'VIEWING_REQUEST_PB',
                'LID' => 's2',
                'LANGUAGE_ID' => 'ru',
                'SITE_TEMPLATE_ID' => 'email_dispatch',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'SUBJECT' => 'Добавлена заявка через форму обратной связи',
                'BODY_TYPE' => 'html',
                'MESSAGE' =>
                    "Имя - #UF_NAME# </br>\n" .
                    "Фамилия - #UF_SURNAME# </br>\n" .
                    "Email - #UF_EMAIL# </br>\n" .
                    "Телефон - #UF_PHONE# </br>\n" .
                    "Название компании - #UF_COMPANY_NAME# </br>\n" .
                    "Страна - #UF_COUNTRY# </br>\n" .
                    "Комментарий - #UF_COMMENT# </br>\n" .
                    "Источник - <a href=\"#UF_URL_DIAMOND#\">#UF_URL_DIAMOND#</a> #LOTS# </br>\n"
            ],
        ]);

        if (!$resultMessage->isSuccess()) {
            return;
        }

        $listSite = ['s2'];
        $id = $resultMessage->getId();
        foreach ($listSite as $siteID) {
            EventMessageSiteTable::add([
                'fields' => [
                    'EVENT_MESSAGE_ID' => $id,
                    'SITE_ID' => $siteID,
                ],
            ]);
        }

        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => 'VIEWING_REQUEST_PB',
                'LID' => 's1',
                'LANGUAGE_ID' => 'en',
                'SITE_TEMPLATE_ID' => 'email_dispatch',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'SUBJECT' => 'A request was added in feedback form',
                'BODY_TYPE' => 'html',
                'MESSAGE' =>
                    "Name - #UF_NAME# </br>\n" .
                    "Surname - #UF_SURNAME# </br>\n" .
                    "Email - #UF_EMAIL# </br>\n" .
                    "Phone - #UF_PHONE# </br>\n" .
                    "Company name - #UF_COMPANY_NAME# </br>\n" .
                    "Country - #UF_COUNTRY# </br>\n" .
                    "Comment - #UF_COMMENT# </br>\n" .
                    "Source -<a href=\"#UF_URL_DIAMOND#\">#UF_URL_DIAMOND#</a> #LOTS# </br>\n"
            ],
        ]);

        if (!$resultMessage->isSuccess()) {
            return;
        }

        $listSite = ['s1'];
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
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'VIEWING_REQUEST_PB']]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'VIEWING_REQUEST_PB']]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
            db()->query('DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]);

            EventMessageTable::delete($type["ID"]);
        }
    }
}

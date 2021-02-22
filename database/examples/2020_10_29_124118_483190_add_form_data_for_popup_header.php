<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class AddFormDataForPopupHeader20201029124118483190 extends BitrixMigration
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
                'EVENT_NAME' => 'GET_CALLBACK',
                'NAME' => 'Заказать звонок',
                'DESCRIPTION' => "#UF_THEME# - Тема\n" .
                    "#UF_QUESTION# - Вопрос\n" .
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
                'EVENT_NAME' => 'GET_CALLBACK',
                'LID' => ['s1','s2','s3'],
                'LANGUAGE_ID' => 'ru',
                'SITE_TEMPLATE_ID' => 'email_dispatch',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => 'diamond@alrosa.ru',
                'CC' => 'TkachevaAM@alrosa.ru',
                'SUBJECT' => 'Заказать звонок',
                'BODY_TYPE' => 'html',
                'MESSAGE' =>
                    "Тема - #UF_THEME# </br>\n" .
                    "Вопрос - #UF_QUESTION# </br>\n" .
                    "Email - #UF_EMAIL# </br>\n" .
                    "Фамилия - #UF_SURNAME# </br>\n" .
                    "Имя - #UF_NAME# </br>\n",
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
        //
    }
}

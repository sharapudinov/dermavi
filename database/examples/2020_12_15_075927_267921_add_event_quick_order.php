<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class AddEventQuickOrder20201215075927267921 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $eventName = 'QUICK_ORDER';

        $resultType = EventTypeTable::add(
            [
                'fields' => [
                    'LID'         => 'ru',
                    'EVENT_NAME'  => $eventName,
                    'NAME'        => 'Быстрый заказ',
                    'DESCRIPTION' =>
                        "#UF_NAME# - Имя\n" .
                        "#UF_PHONE# - Телефон\n" .
                        "#UF_EMAIL# - Email\n" .
                        "#UF_COMMENT# - Комментарий\n" .
                        "#UF_PRODUCT_LINK# - Ссылка на товар\n" .
                        "#UF_PRODUCT_ID# - Id товара\n",
                ],
            ]
        );
        if (!$resultType->isSuccess()) {
            throw new MigrationException(
                'Ошибка при добавлении типа шаблона: ' . implode(', ', $resultType->getErrorMessages())
            );
        }

        $resultMessage = EventMessageTable::add(
            [
                'fields' => [
                    'EVENT_NAME'       => $eventName,
                    'LID'              => ['s1', 's2', 's3'],
                    'LANGUAGE_ID'      => 'ru',
                    'SITE_TEMPLATE_ID' => 'email_dispatch',
                    'ACTIVE'           => 'Y',
                    'EMAIL_FROM'       => '#DEFAULT_EMAIL_FROM#',
                    'EMAIL_TO'         => 'diamond@alrosa.ru',
                    'CC'               => 'TkachevaAM@alrosa.ru',
                    'SUBJECT'          => 'Быстрый заказ',
                    'BODY_TYPE'        => 'html',
                    'MESSAGE'          =>
                        "Имя - #UF_NAME# </br>\n" .
                        "Телефон - #UF_PHONE# </br>\n" .
                        "Email - #UF_EMAIL# </br>\n" .
                        "Комментарий - #UF_COMMENT# </br>\n" .
                        "Ссылка на товар - #UF_PRODUCT_LINK# </br>\n" .
                        "Id товара - #UF_PRODUCT_ID# </br>\n",
                ],
            ]
        );

        if (!$resultMessage->isSuccess()) {
            return;
        }

        $listSite = ['s1', 's2', 's3'];
        $id = $resultMessage->getId();
        foreach ($listSite as $siteID) {
            EventMessageSiteTable::add(
                [
                    'fields' => [
                        'EVENT_MESSAGE_ID' => $id,
                        'SITE_ID'          => $siteID,
                    ],
                ]
            );
        }
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {
        //
    }
}

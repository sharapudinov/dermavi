<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class EventMessageGetCallbackEdit20201101140000000000 extends BitrixMigration
{
    public const EVENT_TYPE = 'GET_CALLBACK';

    /**
     * @return void
     * @throws \Exception
     */
    public function up(): void
    {

        $eventType = EventTypeTable::getList(
            [
                'filter' => [
                    '=EVENT_NAME' => static::EVENT_TYPE,
                    '=LID' => 'ru',
                ]
            ]
        )->fetch();

        if (!$eventType) {
            return;
        }

        $result = EventTypeTable::update(
            $eventType['ID'],
            [
                'DESCRIPTION' =>
                    "#UF_NAME# - Имя \n" .
                    "#UF_PHONE# - Телефон\n" .
                    "#UF_EMAIL# - E-mail\n" .
                    "#UF_COMMENT# - Комментарий\n"
            ]
        );
        if (!$result->isSuccess()) {
            throw new MigrationException(
                'Ошибка при сохранении типа почтового шаблона: ' . implode(', ', $result->getErrorMessages())
            );
        }

        //
        // ---
        //
        $eventMessage = EventMessageTable::getList(
            [
                'filter' => [
                    '=EVENT_NAME' => static::EVENT_TYPE,
                ]
            ]
        )->fetch();

        if (!$eventMessage) {
            return;
        }

        $result = EventMessageTable::update(
            $eventMessage['ID'],
            [
                'LID' => 's1',
                'MESSAGE' =>
                    "Имя - #UF_NAME# <br>\n" .
                    "Телефон - #UF_PHONE# <br>\n" .
                    "E-mail - #UF_EMAIL# <br>\n" .
                    "Комментарий - #UF_COMMENT# <br>\n"
            ]
        );
        if (!$result->isSuccess()) {
            throw new MigrationException(
                'Ошибка при сохранении почтового шаблона: ' . implode(', ', $result->getErrorMessages())
            );
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function down(): void
    {
        //
    }
}

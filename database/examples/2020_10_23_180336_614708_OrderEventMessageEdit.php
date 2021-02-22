<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class OrderEventMessageEdit20201023180336614708 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $by = "site_id";
        $order = "desc";
        $filter = [
            "TYPE_ID" => [
                "ORDER_STATUS_UPDATE"
            ]
        ];

        $rsMess = CEventMessage::GetList(
            $by,
            $order,
            $filter
        );

        while ($arMess = $rsMess->GetNext()) {
            $event = new CEventMessage;

            $fields = [
                'SUBJECT' => str_replace('#ORDER_ID#', '#ORDER_ACCOUNT_NUMBER#', $arMess['SUBJECT'])
            ];

            if ($event->Update($arMess['ID'], $fields)) {
                echo 'Почтовый шаблон ' . $arMess['ID'] . ' успешно обновлён.' . PHP_EOL;
            }
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

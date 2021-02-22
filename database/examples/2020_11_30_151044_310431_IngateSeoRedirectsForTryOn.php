<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Ingate\Seo\RedirectTable;
use Bitrix\Main\Type\Datetime;

class IngateSeoRedirectsForTryOn20201130151044310431 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $data = [
            [
                'ACTIVE' => 'Y',
                'OLD' => '/en/customer-service/try-on/',
                'NEW' => '/en/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/cn/customer-service/try-on/',
                'NEW' => '/cn/',
                'STATUS' => '302'
            ]
        ];

        foreach ($data as $arFields) {
            $arFields['TIMESTAMP_X'] = new DateTime();
            $arFields['DATE_CREATE'] = new DateTime();
            $result = RedirectTable::add($arFields);

            if (!$result->isSuccess()) {
                echo implode('<br>',$result->getErrorMessages()).PHP_EOL;
            }
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

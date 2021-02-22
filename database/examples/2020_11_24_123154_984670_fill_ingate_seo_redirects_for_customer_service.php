<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Application;
use Bitrix\Main\Type\Datetime;
use Ingate\Seo\RedirectTable;

class FillIngateSeoRedirectsForCustomerService20201124123154984670 extends BitrixMigration
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
                'OLD' => '/en/customer-service/warranty/',
                'NEW' => '/en/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/cn/customer-service/warranty/',
                'NEW' => '/cn/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/en/customer-service/payment-and-shipping/',
                'NEW' => '/en/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/cn/customer-service/payment-and-shipping/',
                'NEW' => '/cn/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/en/customer-service/certification/',
                'NEW' => '/en/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/cn/customer-service/certification/',
                'NEW' => '/cn/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/en/customer-service/personalization/',
                'NEW' => '/en/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/cn/customer-service/personalization/',
                'NEW' => '/cn/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/en/customer-service/questions-and-answers/',
                'NEW' => '/en/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/cn/customer-service/questions-and-answers/',
                'NEW' => '/cn/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/en/customer-service/for-partners/',
                'NEW' => '/en/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/cn/customer-service/for-partners/',
                'NEW' => '/cn/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/en/customer-service/get-in-touch/',
                'NEW' => '/en/',
                'STATUS' => '302'
            ],
            [
                'ACTIVE' => 'Y',
                'OLD' => '/cn/customer-service/get-in-touch/',
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
        $conn = Application::getConnection();
        $conn->query('truncate `ingate_seo_redirects`');
    }

}

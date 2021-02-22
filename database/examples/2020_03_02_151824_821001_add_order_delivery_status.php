<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;

/**
 * Класс, описывающий миграцию для добавления статуса заказа "На доставке"
 * Class AddOrderDeliveryStatus20200302151824821001
 */
class AddOrderDeliveryStatus20200302151824821001 extends BitrixMigration
{
    /** @var string Идентификатор статуса */
    private const STATUS_ID = 'OD';

    /**
     * AddOrderDeliveryStatus20200302151824821001 constructor.
     */
    public function __construct()
    {
        Loader::includeModule('sale');
        parent::__construct();
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CSaleStatus())->Add([
            'ID' => self::STATUS_ID,
            'SORT' => '120',
            'LANG' => [
                [
                    'LID' => 'en',
                    'NAME' => 'On delivery'
                ],
                [
                    'LID' => 'ru',
                    'NAME' => 'На доставке'
                ],
                [
                    'LID' => 'cn',
                    'NAME' => '投递中'
                ]
            ]
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
        CSaleStatus::Delete(self::STATUS_ID);
    }
}

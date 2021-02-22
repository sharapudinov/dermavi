<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, описывающий миграцию для создания почтового события для отправки письма
 * об индивидуальном заказе из конструктора ЮБИ
 * Class AddIndividualOrderJewelryConstructorEventType20200520112432022641
 */
class AddIndividualOrderJewelryConstructorEventType20200520112432022641 extends BitrixMigration
{
    /** @var string Символьный код события */
    private const EVENT_NAME = 'INDIVIDUAL_ORDER_JEWELRY_CONSTRUCTOR';

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
                'EVENT_NAME' => self::EVENT_NAME,
                'NAME' => 'Индивидуальный заказ',
                'DESCRIPTION' => "#EMAIL_TO# - Кому (email)\n
                                        #ORDER_ID# - Идентификатор заказа"
            ]
        ]);
        if (!$resultType->isSuccess()) {
            throw new MigrationException(
                'Ошибка при добавлении типа шаблона: '
                . implode(', ', $resultType->getErrorMessages())
            );
        };

        EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => self::EVENT_NAME,
                'LID' => 's2',
                'LANGUAGE_ID' => 'ru',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'CC' => '',
                'SUBJECT' => 'Индивидуальный заказ ЮБИ',
                'BODY_TYPE' => 'html',
                'MESSAGE' => "<?php EventMessageThemeCompiler::includeComponent('email.dispatch:order.individual.jewelry.constructor', '', [
                    'order_id' => #ORDER_ID#
                    ])?>"
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
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => self::EVENT_NAME]]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => self::EVENT_NAME]]);
        while ($type = $res->fetch()) {
            EventMessageTable::delete($type["ID"]);
        }
    }
}
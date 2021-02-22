<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, описывающий миграцию для разделения почтового шаблона "NEW_ORDER_CREATE" на два разных.
 * Отдельно для бриллиантов и отдельно для ЮБИ
 * Class SplitOrderManagerMailEventToDifferent20200526112740788489
 */
class SplitOrderManagerMailEventToDifferent20200526112740788489 extends BitrixMigration
{
    /** @var string Символьный код почтового события для заказов ЮБИ */
    private const EVENT_NAME = 'NEW_JEWELRY_ORDER_CREATE';

    /** @var string Символьный код почтового события для заказов бриллиантов */
    private const DIAMONDS_EVENT_NAME = 'NEW_ORDER_CREATE';

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
                'NAME' => 'Создан новый заказ ЮБИ',
                'DESCRIPTION' => "#EMAIL_TO# - Email пользователя\n" .
                    "#ORDER_ID# - Идентификатор заказа\n"
            ]
        ]);
        if (!$resultType->isSuccess()) {
            throw new MigrationException(
                'Ошибка при добавлении типа шаблона: '
                . implode(', ', $resultType->getErrorMessages())
            );
        }
        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => self::EVENT_NAME,
                'LID' => 's2',
                'LANGUAGE_ID' => 'ru',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => get_sprint_option('KRISTAL_EMAIL'),
                'CC' => 'k.stepanenkov@smolenskdiamonds.ru',
                'SUBJECT' => 'Алроса: новый заказ',
                'BODY_TYPE' => 'html',
                'MESSAGE' => "<?EventMessageThemeCompiler::includeComponent(
	\"email.dispatch:order.manager\",
	\"\",
	[
		\"mode\" => #MODE#,
		\"order_id\" => #ORDER_ID#,
		\"update_text\" => #UPDATE_TEXT#
	]
);?>"
            ],
        ]);

        EventMessageSiteTable::add([
            'fields' => [
                'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                'SITE_ID' => 's2',
            ],
        ]);


        $eventMessageData = EventMessageTable::getList([
            'filter' => ['EVENT_NAME' => self::DIAMONDS_EVENT_NAME],
        ])->fetch();

        EventMessageTable::update(
            $eventMessageData['ID'],
            [
                'EMAIL_TO' => get_sprint_option('EMAIL_DIAMONDS'),
            ]
        );
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
            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
            db()->query(
                'DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]
            );

            EventMessageTable::delete($type["ID"]);
        }

        $eventMessageData = EventMessageTable::getList([
            'filter' => ['EVENT_NAME' => self::DIAMONDS_EVENT_NAME],
        ])->fetch();

        EventMessageTable::update(
            $eventMessageData['ID'],
            [
                'EMAIL_TO' => '#EMAIL_TO#',
            ]
        );
    }
}

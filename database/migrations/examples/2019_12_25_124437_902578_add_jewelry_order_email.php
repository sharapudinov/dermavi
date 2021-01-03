<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddJewelryOrderEmail20191225124437902578 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $resultMessage = \Bitrix\Main\Mail\Internal\EventMessageTable::add([
            'fields' => [
                'EVENT_NAME'       => 'FORM_FILLING_JEWELRY_ORDER',
                'LID'              => 's2',
                'LANGUAGE_ID'      => 'ru',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE'           => 'Y',
                'EMAIL_FROM'       => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO'         => '#EMAIL_TO#',
                'CC'               => '',
                'SUBJECT'          => 'Алроса: Заказ ювелирного изделия',
                'BODY_TYPE'        => 'html',
                'MESSAGE'          => "<?php 
EventMessageThemeCompiler::includeComponent(
    'email.dispatch:order.jewelry.manager', 
    '', 
    [
        'product_id' => #product_id#,
        'firstName' => #user_firstname#,
        'lastName' => #user_lastname#,
        'secondName' => #user_secondname#,
        'country' => #user_country#,
        'email' => #user_email#,
        'phone' => #user_phone#,
        'engraving' => #engraving#,
        'currency' => #currency#,
        'date' => #RS_DATE_CREATE#,
    ]);
?>"
            ],
        ]);

        $resultMessage = \Bitrix\Main\Mail\Internal\EventMessageTable::add([
            'fields' => [
                'EVENT_NAME'       => 'FORM_FILLING_JEWELRY_ORDER',
                'LID'              => 's2',
                'LANGUAGE_ID'      => 'ru',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE'           => 'Y',
                'EMAIL_FROM'       => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO'         => '#user_email#',
                'CC'               => '',
                'SUBJECT'          => 'Алроса: Заказ ювелирного изделия',
                'BODY_TYPE'        => 'html',
                'MESSAGE'          => "<?php 
EventMessageThemeCompiler::includeComponent(
    'email.dispatch:order.jewelry.user', 
    '', 
    [
        'product_id' => #product_id#,
        'firstName' => #user_firstname#,
        'lastName' => #user_lastname#,
        'secondName' => #user_secondname#,
        'country' => #user_country#,
        'email' => #user_email#,
        'phone' => #user_phone#,
        'engraving' => #engraving#,
        'currency' => #currency#,
        'date' => #RS_DATE_CREATE#,
    ]);
?>"
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
        $CDBResult = CEventMessage::GetList($by, $order, ["EVENT_NAME" => "FORM_FILLING_JEWELRY_ORDER"]);
        while($item = $CDBResult->Fetch()) {
            CEventMessage::Delete($item["ID"]);
        }
    }
}

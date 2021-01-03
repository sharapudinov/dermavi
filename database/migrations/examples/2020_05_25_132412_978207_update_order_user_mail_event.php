<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageTable;

/**
 * Класс, описывающий миграцию для обновления события NEW_ORDER_CREATE_USER
 * Class UpdateOrderUserMailEvent20200525132412978207
 */
class UpdateOrderUserMailEvent20200525132412978207 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var string $messageText Текст шаблона почтового */
        $messageText = "<?php 
EventMessageThemeCompiler::includeComponent(
    #COMPONENT_NAME#,
    '',
    [
        'order_id' => #ORDER_ID#,
        'component' => 'email.dispatch:order.new.user'
    ]);
?>";

        $result           = EventMessageTable::getList(['filter' => ['EVENT_NAME' => 'NEW_ORDER_CREATE_USER'],]);

        while($eventMessageData = $result->fetch()){
            EventMessageTable::update($eventMessageData['ID'],
                [
                    'MESSAGE' => $messageText,
                ]
            );
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
        $result = EventMessageTable::getList(['filter' => ['EVENT_NAME' => 'NEW_ORDER_CREATE_USER']]);

        while($eventMessageData = $result->fetch()) {
            EventMessageTable::update($eventMessageData['ID'],
                [
                    'MESSAGE' => "<?php 
EventMessageThemeCompiler::includeComponent(
    'email.dispatch:order.user.index', 
    '', 
    [
        'order_id' => #ORDER_ID#,
        'component' => 'email.dispatch:order.new.user'
    ]);
?>",
                ]
            );
        }
    }
}
<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageTable;

class Task46458ChangeEmailTemplates20191021173422105501 extends BitrixMigration
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
    'email.dispatch:order.diamond.manager', 
    '', 
    [
        'order_id' => #ORDER_ID#,
        'order_cost' => #ORDER_COST#,
        'update_text' => #UPDATE_TEXT#,
    ]);
?>";


        $eventMessageData = EventMessageTable::getList(['filter' => ['EVENT_NAME' => 'NEW_ORDER_CREATE'],])->fetch();

        EventMessageTable::update($eventMessageData['ID'],
            [
                'MESSAGE' => $messageText,
            ]
        );

        $eventMessageData = EventMessageTable::getList(['filter' => ['EVENT_NAME' => 'ORDER_LIST_UPDATE_MANAGER'],])->fetch();

        EventMessageTable::update($eventMessageData['ID'],
            [
                'MESSAGE' => $messageText,
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
        $eventMessageData = EventMessageTable::getList(['filter' => ['EVENT_NAME' => 'NEW_ORDER_CREATE'],])->fetch();

        EventMessageTable::update($eventMessageData['ID'],
            [
                'MESSAGE' => "<p>Номер заказа: #ORDER_ID#</p>
<p>Дата заказа: #ORDER_DATE#</p>
<p>Имя заказчика: #USER_NAME#</p>
<p>Email: #EMAIL_TO#</p>
<p>Сумма заказа: #PRICE#</p>
<p>Ссылка на заказ: #REQUEST_URL#</p>
",
            ]
        );

        $eventMessageData = EventMessageTable::getList(['filter' => ['EVENT_NAME' => 'ORDER_LIST_UPDATE_MANAGER'],])->fetch();

        EventMessageTable::update($eventMessageData['ID'],
            [
                'MESSAGE' => "<h2>Обновлен состав заказа ##ORDER_ID#</h2>
                    <p>Имя заказчика: #USER_NAME#</p>
                    <p>Email: #EMAIL_TO#</p>
                    <p>Ссылка на заказ: #REQUEST_URL#</p>
",
            ]
        );

    }
}

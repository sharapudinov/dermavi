<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageTable;

class ChangeOrderEmailTemplate20200121112859117772 extends BitrixMigration
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
    'email.dispatch:order.user.index', 
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
        $eventMessageData = EventMessageTable::getList(['filter' => ['EVENT_NAME' => 'NEW_ORDER_CREATE_USER'],'LID'=>'s2'])->fetch();

        EventMessageTable::update($eventMessageData['ID'],
            [
                'MESSAGE' => "<p>Здравствуйте, #USER_NAME#.</p><p>Ваш заказ №#ORDER_ID# на сайте #SITE_URL# успешно создан</p><p>Дата заказа: #ORDER_DATE#</p><p>Итоговая стоимость: #ORDER_COST#</p><p>Мы приступили к обработке вашего заказа, скоро с вами свяжется ваш менеджер.</p>",
            ]
        );

        $eventMessageData = EventMessageTable::getList(['filter' => ['EVENT_NAME' => 'NEW_ORDER_CREATE_USER'],'LID'=>'s1'])->fetch();

        EventMessageTable::update($eventMessageData['ID'],
            [
                'MESSAGE' => "<p>Hello, #USER_NAME#.</p><p>Your order №#ORDER_ID# had been accepted</p><p>Order date: #ORDER_DATE#</p><p>Order cost: #ORDER_COST#</p><p>We have started processing your order, your manager will contact you soon.</p>",
            ]
        );
    }
}

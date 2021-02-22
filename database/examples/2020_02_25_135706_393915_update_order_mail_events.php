<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageTable;

/**
 * Класс, описывающий миграцию для изменения содержимого писем о заказе
 * Class UpdateOrderMailEvents20200225135706393915
 */
class UpdateOrderMailEvents20200225135706393915 extends BitrixMigration
{
    /**
     * Обновляет письмо
     *
     * @param string $eventName Имя события
     * @param string $message Тело письма
     *
     * @return void
     */
    private function update(string $eventName, string $message): void
    {
        $eventMessageData = EventMessageTable::getList([
            'filter' => ['EVENT_NAME' => $eventName],
        ])->fetch();

        EventMessageTable::update(
            $eventMessageData['ID'],
            [
                'MESSAGE' => $message,
            ]
        );
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->update('NEW_ORDER_CREATE', '<?php 
EventMessageThemeCompiler::includeComponent(
    \'email.dispatch:order.manager\', 
    \'\', 
    [
        \'order_id\' => #ORDER_ID#,
        \'order_cost\' => #ORDER_COST#,
        \'update_text\' => #UPDATE_TEXT#,
    ]);
?>');

        $this->update('ORDER_LIST_UPDATE_MANAGER', '<?php 
EventMessageThemeCompiler::includeComponent(
    \'email.dispatch:order.manager\', 
    \'\', 
    [
        \'order_id\' => #ORDER_ID#,
        \'update_text\' => #UPDATE_TEXT#,
    ]);
?>');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->update('NEW_ORDER_CREATE', '<?php 
EventMessageThemeCompiler::includeComponent(
    \'email.dispatch:order.diamond.manager\', 
    \'\', 
    [
        \'order_id\' => #ORDER_ID#,
        \'order_cost\' => #ORDER_COST#,
        \'update_text\' => #UPDATE_TEXT#,
    ]);
?>');

        $this->update('ORDER_LIST_UPDATE_MANAGER', '<?php 
EventMessageThemeCompiler::includeComponent(
    \'email.dispatch:order.diamond.manager\', 
    \'\', 
    [
        \'order_id\' => #ORDER_ID#,
        \'update_text\' => #UPDATE_TEXT#,
    ]);
?>');
    }
}

<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageTable;

/**
 * Класс, описывающий миграцию для обновления почтовых шаблонов о заказе для менеджера
 * Class UpdateOrderManagerMailTemplates20200311142645815816
 */
class UpdateOrderManagerMailTemplates20200311142645815816 extends BitrixMigration
{
    /**
     * Обновляет письмо
     *
     * @param string $eventName Имя события
     * @param array $fields Поля для обновления
     *
     * @return void
     */
    private function update(string $eventName, array $fields): void
    {
        $eventMessageData = EventMessageTable::getList([
            'filter' => ['EVENT_NAME' => $eventName],
        ])->fetch();

        EventMessageTable::update(
            $eventMessageData['ID'],
            $fields
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
        $this->update('NEW_ORDER_CREATE', [
            'EMAIL_TO' => '#EMAIL_TO#',
            'MESSAGE' => '<?php 
EventMessageThemeCompiler::includeComponent(
    \'email.dispatch:order.manager\', 
    \'\', 
    [
        \'order_id\' => #ORDER_ID#,
        \'update_text\' => #UPDATE_TEXT#,
        \'mode\' => #MODE#
    ]);
?>']);

        $this->update('ORDER_LIST_UPDATE_MANAGER', [
            'EMAIL_TO' => '#EMAIL_TO#',
            'MESSAGE' => '<?php 
EventMessageThemeCompiler::includeComponent(
    \'email.dispatch:order.manager\', 
    \'\', 
    [
        \'order_id\' => #ORDER_ID#,
        \'update_text\' => #UPDATE_TEXT#,
        \'mode\' => #MODE#
    ]);
?>']);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->update('NEW_ORDER_CREATE', ['MESSAGE' => '<?php 
EventMessageThemeCompiler::includeComponent(
    \'email.dispatch:order.manager\', 
    \'\', 
    [
        \'order_id\' => #ORDER_ID#,
        \'order_cost\' => #ORDER_COST#,
        \'update_text\' => #UPDATE_TEXT#
    ]);
?>']);
        $this->update('ORDER_LIST_UPDATE_MANAGER', ['MESSAGE' => '<?php 
EventMessageThemeCompiler::includeComponent(
    \'email.dispatch:order.manager\', 
    \'\', 
    [
        \'order_id\' => #ORDER_ID#,
        \'update_text\' => #UPDATE_TEXT#,
    ]);
?>']);
    }
}

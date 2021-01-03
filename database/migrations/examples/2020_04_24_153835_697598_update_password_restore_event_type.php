<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageTable;

/**
 * Класс, описывающий миграцию для обновления почтового события "Восстановление пароля"
 * Class UpdatePasswordRestoreEventType20200424153835697598
 */
class UpdatePasswordRestoreEventType20200424153835697598 extends BitrixMigration
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
        ]);

        while ($message = $eventMessageData->fetch()) {
            EventMessageTable::update(
                $message['ID'],
                $fields
            );
        }
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->update('USER_PASSWORD_RESTORE_LINK', [
            'MESSAGE' => '<?php EventMessageThemeCompiler::includeComponent(\'email.dispatch:user.restore.password\', \'\', [
\'request_url\' => #REQUEST_URL#,
\'email\' => #EMAIL_TO#
])?>']);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->update('USER_PASSWORD_RESTORE_LINK', [
            'MESSAGE' => '<?php EventMessageThemeCompiler::includeComponent(\'email.dispatch:user.restore.password\', \'\', [
\'request_url\' => #REQUEST_URL#,
])?>']);
    }
}

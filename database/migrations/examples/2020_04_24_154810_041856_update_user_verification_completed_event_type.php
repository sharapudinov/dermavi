<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageTable;

/**
 * Класс, описывающий миграцию для обновления почтового события "Проверка данных завершена"
 * Class UpdateUserVerificationCompletedEventType20200424154810041856
 */
class UpdateUserVerificationCompletedEventType20200424154810041856 extends BitrixMigration
{
    /**
     * Обновляет письмо
     *
     * @param array $fields Поля для обновления
     *
     * @return void
     */
    private function update(array $fields): void
    {
        $eventMessageData = EventMessageTable::getList([
            'filter' => ['EVENT_NAME' => 'CRM_VERIFICATION_COMPLETED'],
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
        $this->update(['MESSAGE' => '<?php EventMessageThemeCompiler::includeComponent(\'email.dispatch:user.verification.completed\', \'\', [\'email\' => #EMAIL_TO#])?>']);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->update(['MESSAGE' => '<?php EventMessageThemeCompiler::includeComponent(\'email.dispatch:user.verification.completed\', \'\', [])?>']);
    }
}

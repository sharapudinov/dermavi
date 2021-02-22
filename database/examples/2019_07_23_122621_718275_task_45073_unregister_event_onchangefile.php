<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class Task45073UnregisterEventOnchangefile20190723122621718275 extends BitrixMigration
{
    /**
     * Run the migration.
     * Убираем родной обработчик битрикса
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        \Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler('main', 'OnChangeFile', 'main', 'CMain',
            'OnChangeFileComponent');
    }

    /**
     * Reverse the migration.
     * Возвращаем родной обработчик битрикс
     * тоже что и тут// modules/main/install/index.php : 340
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        RegisterModuleDependences('main', 'OnChangeFile', 'main', 'CMain', 'OnChangeFileComponent');
    }
}

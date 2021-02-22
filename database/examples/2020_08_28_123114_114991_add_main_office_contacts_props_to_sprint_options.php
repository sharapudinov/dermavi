<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;
use Sprint\Options\Module;

/** @noinspection PhpUnused */

class AddMainOfficeContactsPropsToSprintOptions20200828123114114991 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws Exception
     */
    public function up()
    {
        Loader::includeModule('sprint.options');

        $data = [
            'PHONE_MAIN_OFFICE_B2B' => '+74951234567'
            ,
            'EMAIL_MAIN_OFFICE_B2B' => 'b2borders@alrosa.ru'
        ];

        foreach ($data as $key => $value) {
            Module::setDbOption($key, $value);
        }

        return true;
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws Exception
     */
    public function down()
    {
        $data = [
            'PHONE_MAIN_OFFICE_B2B' => '+74951234567'
            ,
            'EMAIL_MAIN_OFFICE_B2B' => 'b2borders@alrosa.ru'
        ];

        foreach ($data as $key => $value) {
            Module::resetDbOptions($key, $value);
        }
        return true;
    }
}

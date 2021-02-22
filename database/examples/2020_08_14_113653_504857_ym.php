<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;
use Sprint\Options\Module;

/** @noinspection PhpUnused */

class Ym20200814113653504857 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws Exception
     */
    public function up()
    {
        if (!in_production()) {
            return true;
        }

        Loader::includeModule('sprint.options');

        $data = [
            'YANDEX_METRIKA_ID' => 52108288,
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
        return true;
    }
}

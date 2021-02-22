<?php

use App\Models\Auxiliary\CRM\Region;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для создания мультиязычного названия региона в таблице "Регион"
 * Class MakeNameFieldMultilanguageInRegionTable20190918110501247409
 */
class MakeNameFieldMultilanguageInRegionTable20190918110501247409 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        Region::truncate();
        db()->query('ALTER TABLE ' . (new Region())->table . ' DROP COLUMN name');
        db()->query('ALTER TABLE ' . (new Region())->table . ' ADD COLUMN name_ru VARCHAR(255) AFTER code');
        db()->query('ALTER TABLE ' . (new Region())->table . ' ADD COLUMN name_en VARCHAR(255) AFTER name_ru');
        db()->query('ALTER TABLE ' . (new Region())->table . ' ADD COLUMN name_cn VARCHAR(255) AFTER name_en');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        Region::truncate();
        db()->query('ALTER TABLE ' . (new Region())->table . ' DROP COLUMN name_ru');
        db()->query('ALTER TABLE ' . (new Region())->table . ' DROP COLUMN name_en');
        db()->query('ALTER TABLE ' . (new Region())->table . ' DROP COLUMN name_cn');
        db()->query('ALTER TABLE ' . (new Region())->table . ' ADD COLUMN name VARCHAR(255) AFTER code');
    }
}

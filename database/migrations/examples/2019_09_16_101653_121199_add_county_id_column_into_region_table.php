<?php

use App\Models\Auxiliary\CRM\Region;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для создания столбца "Идентификатор страны" в таблице "Регион"
 * Class AddCountyIdColumnIntoRegionTable20190916101653121199
 */
class AddCountyIdColumnIntoRegionTable20190916101653121199 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        db()->query('ALTER TABLE ' . (new Region())->table . ' ADD COLUMN country_id VARCHAR(255) AFTER name');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        db()->query('ALTER TABLE ' . (new Region())->table . ' DROP COLUMN country_id');
    }
}

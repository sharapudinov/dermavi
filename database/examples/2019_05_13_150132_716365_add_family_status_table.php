<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddFamilyStatusTable20190513150132716365 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        db()->query('CREATE TABLE IF NOT EXISTS app_crm_family_status (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(255) NOT NULL,
            status_value VARCHAR(255) NOT NULL
        )');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        db()->query('DROP TABLE IF EXISTS app_crm_family_status');
    }
}

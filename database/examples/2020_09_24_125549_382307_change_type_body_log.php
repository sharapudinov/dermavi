<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class ChangeTypeBodyLog20200924125549382307 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $this->db->query("ALTER TABLE `adv_logging_email` CHANGE `UF_BODY` `UF_BODY` longtext;");
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {
        //
    }
}

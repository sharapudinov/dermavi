<?php

use App\Models\Itg\UserAlias;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateItgUserAliasesTable20201222095901781225 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS " . (new UserAlias())->getTable() . "(" .
                         "id INT AUTO_INCREMENT PRIMARY KEY, " .
                         "user_id INT, " .
                         "contact_id INT" .
                         ")"
        );
        $this->db->query("ALTER TABLE " . (new UserAlias())->getTable() .  " ADD UNIQUE KEY (user_id, contact_id)");
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS ' . (new UserAlias())->getTable());
    }
}

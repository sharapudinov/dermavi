<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use App\Models\Auxiliary\CRM\OtherContactType;
use App\Models\Itg\OrderAlias;

class CreateItgOrderAliasesTable20201222095833410970 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        db()->query("CREATE TABLE IF NOT EXISTS " . (new OrderAlias())->getTable() . "(" .
                    "id INT AUTO_INCREMENT PRIMARY KEY, " .
                    "order_id INT, " .
                    "deal_id INT" .
                    ")"
        );
        db()->query("ALTER TABLE " . (new OrderAlias())->getTable() .  " ADD UNIQUE KEY (order_id, deal_id)");
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        db()->query('DROP TABLE IF EXISTS ' . (new OrderAlias())->getTable());
    }
}

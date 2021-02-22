<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class DeleteCompanyActivityIBlock20200514165752461248 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->deleteIblockByCode('company_activity');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        //
    }
}

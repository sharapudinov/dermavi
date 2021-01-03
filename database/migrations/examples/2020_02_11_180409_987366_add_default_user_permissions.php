<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddDefaultUserPermissions20200211180409987366 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var \App\Models\User[] $users */
        $users = \App\Models\User::getList();

        foreach ($users as $user) {

            if ($user->isLegalEntity()) {
                $user->update([
                    'UF_PURCHASE_UP_100'   => true,
                    'UF_PURCHASE_UP_600'   => true,
                    'UF_PURCHASE_OVER_600' => true,
                ]);
            } else {
                $user->update([
                    'UF_PURCHASE_UP_100' => true,
                ]);
            }
        }
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

<?php

use App\Models\HL\Address as AddressModel;
use App\Models\HL\AddressType;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddPhysicalAddressToExistUsers20200211153249317101 extends BitrixMigration
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
            if (!$user->isLegalEntity() && !$user->physicAddress && $user->getCountry()) {
                AddressModel::create([
                    'UF_USER_ID' => $user->getId(),
                    'UF_COUNTRY' => $user->getCountry(),
                    'UF_TYPE_ID' => AddressType::getPhysicAddressType()->getId()
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

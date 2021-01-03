<?php

use App\Helpers\TTL;
use App\Models\HL\Address as AddressModel;
use App\Models\HL\AddressType;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class FixCompanyAddress20200212122657047218 extends BitrixMigration
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

        /** @var AddressType $addressType - Модель юридического типа адреса */
        $addressType = AddressType::getLegalAddressType();

        foreach ($users as $user) {

            if ($user->isLegalEntity() && $user->country && $user->company && !$user->company->address) {
                /** @var AddressModel $address - Модель созданного адреса */
                $address = AddressModel::create([
                    'UF_COUNTRY' => $user->country->getId(),
                    'UF_TYPE_ID' => $addressType->getId()
                ]);

                $user->company->update(['UF_ADDRESS_ID'=>$address->getId()]);
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

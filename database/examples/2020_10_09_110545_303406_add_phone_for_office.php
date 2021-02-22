<?php

use App\Models\About\Office;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddPhoneForOffice20201009110545303406 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $office = Office::filter(['NAME' => 'Jewelry Smolensk'])->first();
        $office->update(
            [
                'PROPERTY_PHONE_FOR_CLIENTS_VALUE' => [
                    '8 (800) 200-88-01',
                    '+7 (4812) 20-69-20',
                ],
            ]
        );
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

<?php

use App\Models\About\Office;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class FixContactPage20201112144554233920 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $office_moskow = Office::filter(['NAME' => 'Магазин в Москве'])->first();
        $office_moskow->update(
            [
                'PROPERTY_ADDRESS_EN_VALUE' => '12 Smolnaya St., 125493, Moscow'
            ]
        );

        $office_smolensk = Office::filter(['NAME' => 'Jewelry Smolensk'])->first();
        $office_smolensk->update(
            [
                'PROPERTY_ADDRESS_EN_VALUE' => '12 Smolnaya St., 125493, Moscow'
            ]
        );

        $office_piter = Office::filter(['NAME' => 'Магазин в Санкт-Петербурге'])->first();
        $office_piter->update(
            [
                'PROPERTY_ADDRESS_EN_VALUE' => '12 Smolnaya St., 125493, Moscow'
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

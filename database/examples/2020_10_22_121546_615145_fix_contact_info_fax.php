<?php

use App\Models\About\Office;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class FixContactInfoFax20201022121546615145 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $office_global = Office::filter(['NAME' => 'Jewelry Smolensk'])->first();
        $office_global->update(
            [
                'PROPERTY_FAX_VALUE' => '+7 (495) 777-10-80'
            ]
        );

        $office = Office::filter(['NAME' => 'Магазин в Смоленске'])->first();
        $office->update(
            [
                'PROPERTY_FAX_VALUE' => ''
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

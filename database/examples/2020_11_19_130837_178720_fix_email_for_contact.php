<?php

use App\Models\About\Office;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class FixEmailForContact20201119130837178720 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        // заполняю свойство B2C номерами, для центрального офиса
        $office_moskow = Office::filter(['NAME' => 'Магазин в Санкт-Петербурге'])->first();
        $office_moskow->update(
            [
                'PROPERTY_PROPERTY_EMAIL_FOR_LINE_VALUE' => 'polished-auction@alrosa.ru',
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

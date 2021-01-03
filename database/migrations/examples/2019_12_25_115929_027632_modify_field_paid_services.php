<?php

use App\Models\Catalog\PaidService;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class ModifyFieldPaidServices20191225115929027632 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $property = CIBlockProperty::GetList([], [
            'IBLOCK_ID' => PaidService::iblockId(),
            'CODE' => 'FOR_FREE_FROM'
        ])->Fetch();
        (new CIBlockProperty)->Update($property['ID'], [
            'NAME' => 'Бесплатно от суммы',
            'CODE' => 'FOR_FREE_FROM',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Money'
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $property = CIBlockProperty::GetList([], [
            'IBLOCK_ID' => PaidService::iblockId(),
            'CODE' => 'FOR_FREE_FROM'
        ])->Fetch();
        (new CIBlockProperty)->Update($property['ID'], [
            'NAME' => 'Бесплатно от суммы (В долларах США)',
            'CODE' => 'FOR_FREE_FROM',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => ''
        ]);
    }
}

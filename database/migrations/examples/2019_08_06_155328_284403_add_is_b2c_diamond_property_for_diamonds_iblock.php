<?php

use App\Models\Catalog\Diamond;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddIsB2cDiamondPropertyForDiamondsIblock20190806155328284403 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlockProperty)->Add([
            'NAME' => 'Товар для физ лиц',
            'ACTIVE' => 'Y',
            'CODE' => 'IS_FOR_PHYSICAL',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'IBLOCK_ID' => Diamond::iblockID(),
            'VALUES' => [
                [
                    'VALUE' => 'Y',
                    'DEF' => 'N',
                    'SORT' => '100'
                ]
            ]
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
        $propertyId = CIBlockProperty::GetList([], [
            'IBLOCK_ID' => Diamond::iblockID(),
            'CODE' => 'IS_FOR_PHYSICAL'
        ])->Fetch()['ID'];
        CIBlockProperty::Delete($propertyId);
    }
}

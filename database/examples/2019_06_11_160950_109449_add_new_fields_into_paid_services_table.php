<?php

use App\Models\Catalog\PaidService;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class AddNewFieldsIntoPaidServicesTable20190611160950109449 extends BitrixMigration
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
            'NAME' => 'Максимальное количество',
            'CODE' => 'MAXIMUM_COUNT',
            'HINT' => 'К примеру, на сертификате - это 1 шт, а на гравироке - 1 символ. Указывется числом без единиц измерения',
            'PROPERTY_TYPE' => 'N',
            'SORT' => '300',
            'IBLOCK_ID' => PaidService::iblockId()
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Бесплатно от суммы (В долларах США)',
            'CODE' => 'FOR_FREE_FROM',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '310',
            'IBLOCK_ID' => PaidService::iblockId()
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
        $property = CIBlockProperty::GetList(
            [],
            ['IBLOCK_ID' => PaidService::iblockId(), 'CODE' => 'MAXIMUM_COUNT']
        )->Fetch()['ID'];
        CIBlockProperty::Delete($property);

        $property = CIBlockProperty::GetList(
            [],
            ['IBLOCK_ID' => PaidService::iblockId(), 'CODE' => 'FOR_FREE_FROM']
        )->Fetch()['ID'];
        CIBlockProperty::Delete($property);
    }
}

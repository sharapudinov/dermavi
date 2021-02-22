<?php

use App\Models\About\Office;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class ChangeOfficeCountryCodePropertyToMultilanguage20190429182225709480 extends BitrixMigration
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
            'IBLOCK_ID' => Office::iblockId(),
            'CODE' => 'COUNTRY_CODE'
        ])->Fetch();
        (new CIBlockProperty)->Update($property['ID'], [
            'NAME' => 'Код страны (рус)',
            'CODE' => 'COUNTRY_CODE_RU',
            'SORT' => '507',
            'IBLOCK_ID' => Office::iblockId()
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Код страны (англ)',
            'CODE' => 'COUNTRY_CODE_EN',
            'IS_REQUIRED' => 'Y',
            'SORT' => '508',
            'IBLOCK_ID' => Office::iblockId()
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Код страны (кит)',
            'CODE' => 'COUNTRY_CODE_CN',
            'SORT' => '509',
            'IBLOCK_ID' => Office::iblockId()
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
            'IBLOCK_ID' => Office::iblockId(),
            'CODE' => 'COUNTRY_CODE_RU'
        ])->Fetch();
        (new CIBlockProperty)->Update($property['ID'], [
            'NAME' => 'Страна (код)',
            'CODE' => 'COUNTRY_CODE',
            'SORT' => '507',
            'IBLOCK_ID' => Office::iblockId()
        ]);

        $property = CIBlockProperty::GetList([], [
            'IBLOCK_ID' => Office::iblockId(),
            'CODE' => 'COUNTRY_CODE_EN'
        ])->Fetch();
        (new CIBlockProperty)->Delete($property['ID']);

        $property = CIBlockProperty::GetList([], [
            'IBLOCK_ID' => Office::iblockId(),
            'CODE' => 'COUNTRY_CODE_CN'
        ])->Fetch();
        (new CIBlockProperty)->Delete($property['ID']);
    }
}

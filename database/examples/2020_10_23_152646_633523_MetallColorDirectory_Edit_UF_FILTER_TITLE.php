<?php

use App\Models\Jewelry\Dicts\JewelryMetalColor;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class MetallColorDirectoryEditUFFILTERTITLE20201023152646633523 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $valueArray = [
            '1' => 'белое золото',
            '2' => 'желтое золото',
            '3' => 'красное золото',
            '4' => 'белое/желтое золото',
            '5' => 'белое/красное золото',
            '6' => 'желтое/красное золото',
        ];

        /** @var DataManager $strEntityDataClass */
        $strEntityDataClass = HLblock::compileClass(JewelryMetalColor::getTableName());
        $rsData = $strEntityDataClass::getList([]);

        while ($arItem = $rsData->Fetch()) {
            if (array_key_exists($arItem['UF_XML_ID'], $valueArray)) {
                $filterValue = $valueArray[$arItem['UF_XML_ID']];
                $arItem['UF_FILTER_TITLE'] = $filterValue;
                $strEntityDataClass::update($arItem['ID'], $arItem);
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

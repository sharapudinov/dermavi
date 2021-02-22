<?php

use App\Models\Jewelry\Dicts\JewelrySex;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class JewelrySexDirectoryEditUFFILTERTITLE20201023154721898752 extends BitrixMigration
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
            '0' => 'Всех',
            '1' => 'Мужчин',
            '2' => 'Женщин',
        ];

        /** @var DataManager $strEntityDataClass */
        $strEntityDataClass = HLblock::compileClass(JewelrySex::getTableName());
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

<?php

use App\Models\Catalog\HL\CatalogShape;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class EditSortCatalgShapeDict20201120123932062435 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $highloadBlockId = 'HLBLOCK_' . HLblock::getByTableName(CatalogShape::getTableName())['ID'];
        $oUserTypeEntity = new CUserTypeEntity();
        $resIcon = $oUserTypeEntity->GetList([],['ENTITY_ID' => $highloadBlockId, 'XML_ID' => 'UF_ICON'])->getNext();

        // Временно отключаем обязательное поле UF_ICON
        if($resIcon) {
            $oUserTypeEntity->Update($resIcon['ID'], ['MANDATORY' => 'N']);
        }

        $valueArray = [
            'round' => '10',
            'marquis' => '20',
            'pear' => '30',
            'oval' => '40',
            'heart' => '50',
            'princess' => '60',
            'emerald' => '70',
            'asscher' => '80',
            'cushion' => '90',
            'radiant' => '100',
            'trillion' => '110',
            'triangle' => '120',
            'square' => '130',
            'small shield' => '140',
            'phoenix' => '150',
            'barion' => '160',
            'kite' => '170',
            'half moon' => '180',
            'shield' => '190',
            'briolett' => '200',
            'baguette' => '210',
            'fancy shape' => '220',
        ];

        /** @var DataManager $strEntityDataClass */
        $strEntityDataClass = HLblock::compileClass(CatalogShape::getTableName());
        $rsData = $strEntityDataClass::getList([]);

        while ($arItem = $rsData->Fetch()) {
            if (array_key_exists($arItem['UF_XML_ID'], $valueArray)) {
                $arItem['UF_SORT'] = $valueArray[$arItem['UF_XML_ID']];
                $strEntityDataClass::update($arItem['ID'], $arItem);
            } else {
                echo 'not updated ' . $arItem['UF_XML_ID']. PHP_EOL;
            }
        }

        // Включаем обязательное поле UF_ICON
        if($resIcon) {
            $oUserTypeEntity->Update($resIcon['ID'], ['MANDATORY' => 'Y']);
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

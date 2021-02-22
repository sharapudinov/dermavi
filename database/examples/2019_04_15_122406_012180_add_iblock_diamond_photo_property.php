<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class AddIblockDiamondPhotoProperty20190415122406012180 extends BitrixMigration
{
    /**
     * Run the migration
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = $this->getIblockIdByCode('diamond');
        $props = [
            [
                "NAME" => "Фото",
                "SORT" => "100",
                "CODE" => "PHOTOS",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "Y",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
        ];
    
        foreach ($props as $prop) {
            $this->addIblockElementProperty($prop);
        }
    }

    /**
     * Reverse the migration
     * @throws \Exception
     */
    public function down()
    {
        $iblockId = $this->getIblockIdByCode('diamond');
        $this->deleteIblockElementPropertyByCode($iblockId, 'PHOTO');
    }
}

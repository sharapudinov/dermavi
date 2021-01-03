<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class UpdateCatalogDiamond20181221224516366230 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = $this->getIblockIdByCode('diamond');
        $props = [
            [
                "NAME" => "Возраст камня",
                "SORT" => "",
                "CODE" => "AGE",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Высота, значения в процентах от 25 до 80%",
                "SORT" => "",
                "CODE" => "DEPTH",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Площадка, значения в процентах от 25 до 80%",
                "SORT" => "",
                "CODE" => "TABLE",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
        ];

        foreach ($props as $prop) {
            $this->addIblockElementProperty($prop);
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
        $iblockId = $this->getIblockIdByCode('diamond');
        $this->deleteIblockElementPropertyByCode($iblockId, 'AGE');
        $this->deleteIblockElementPropertyByCode($iblockId, 'DEPTH');
        $this->deleteIblockElementPropertyByCode($iblockId, 'TABLE');
    }
}

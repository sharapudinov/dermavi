<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class UpdateCatalogDiamond20181221122305094371 extends BitrixMigration
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
                "NAME" => "Количество камней в пакете",
                "SORT" => "",
                "CODE" => "COUNT",
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
        $this->deleteIblockElementPropertyByCode($iblockId, 'COUNT');
    }
}

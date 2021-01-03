<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class UpdIblockCatalog20190114154105486245 extends BitrixMigration
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
                "NAME" => "Сертификация",
                "SORT" => "",
                "CODE" => "LAB",
                "PROPERTY_TYPE" => "S",
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
        $this->deleteIblockElementPropertyByCode($iblockId, 'LAB');
    }
}

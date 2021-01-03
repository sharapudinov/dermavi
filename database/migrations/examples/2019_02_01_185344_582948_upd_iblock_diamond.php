<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class UpdIblockDiamond20190201185344582948 extends BitrixMigration
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
                "NAME" => "Данные трейсинга",
                "SORT" => "",
                "CODE" => "TRASING_DATA",
                "PROPERTY_TYPE" => "S",
                "ROW_COUNT" => "10",
                "COL_COUNT" => "50",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Доступность трейсинга",
                "SORT" => "",
                "CODE" => "AVAILABLE_TRASING",
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
        $this->deleteIblockElementPropertyByCode($iblockId, 'TRASING_DATA');
        $this->deleteIblockElementPropertyByCode($iblockId, 'AVAILABLE_TRASING');
    }
}

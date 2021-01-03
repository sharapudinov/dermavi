<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class UpdIblockDiamond20190115120643061860 extends BitrixMigration
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
                "NAME" => "Качество полировки (сортировка)",
                "SORT" => "",
                "CODE" => "POLISH_SORT",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Качество огранки (сортировка)",
                "SORT" => "",
                "CODE" => "CUT_SORT",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Кулет (сортировка)",
                "SORT" => "",
                "CODE" => "CULET_SORT",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Цвет (сортировка)",
                "SORT" => "",
                "CODE" => "COLOR_SORT",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Форма (сортировка)",
                "SORT" => "",
                "CODE" => "SHAPE_SORT",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Прозрачность (сортировка)",
                "SORT" => "",
                "CODE" => "CLARITY_SORT",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Симметрия (сортировка)",
                "SORT" => "",
                "CODE" => "SYMMETRY_SORT",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Флюоресценция (сортировка)",
                "SORT" => "",
                "CODE" => "FLUOR_SORT",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Цвет флюоресценции (сортировка)",
                "SORT" => "",
                "CODE" => "FLUOR_COLOR_SORT",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Размер (сортировка)",
                "SORT" => "",
                "CODE" => "SIZE_SORT",
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
        $this->deleteIblockElementPropertyByCode($iblockId, 'POLISH_SORT');
        $this->deleteIblockElementPropertyByCode($iblockId, 'CUT_SORT');
        $this->deleteIblockElementPropertyByCode($iblockId, 'CULET_SORT');
        $this->deleteIblockElementPropertyByCode($iblockId, 'COLOR_SORT');
        $this->deleteIblockElementPropertyByCode($iblockId, 'SHAPE_SORT');
        $this->deleteIblockElementPropertyByCode($iblockId, 'CLARITY_SORT');
        $this->deleteIblockElementPropertyByCode($iblockId, 'SYMMETRY_SORT');
        $this->deleteIblockElementPropertyByCode($iblockId, 'FLUOR_SORT');
        $this->deleteIblockElementPropertyByCode($iblockId, 'FLUOR_COLOR_SORT');
        $this->deleteIblockElementPropertyByCode($iblockId, 'SIZE_SORT');
    }
}

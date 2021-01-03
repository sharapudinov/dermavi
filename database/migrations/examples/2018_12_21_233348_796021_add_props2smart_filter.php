<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Iblock\PropertyIndex\Manager;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Iblock\SectionPropertyTable;
use Bitrix\Main\Application;

class AddProps2smartFilter20181221233348796021 extends BitrixMigration
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
        $this->deleteProps($iblockId);

        $res = PropertyTable::getList([
            'filter' => [
                'IBLOCK_ID' => $iblockId,
            ],
            'select' => [
                'ID',
                'CODE',
            ],
        ]);

        $propsList = [];
        while ($prop = $res->fetch()) {
            $propsList[$prop['CODE']] = $prop['ID'];
        }

        $props = [
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['SHAPE'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_EXPANDED' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::CHECKBOXES,
                'FILTER_HINT' => 'forms diamond',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['WEIGHT'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_EXPANDED' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::NUMBERS_WITH_SLIDER,
                'FILTER_HINT' => 'weight diamond',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['PRICE'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_EXPANDED' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::NUMBERS_WITH_SLIDER,
                'FILTER_HINT' => 'price diamond',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['COLOR'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_EXPANDED' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::CHECKBOXES,
                'FILTER_HINT' => 'color diamond',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['CLARITY'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_EXPANDED' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::CHECKBOXES,
                'FILTER_HINT' => 'clarity diamond',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['CUT'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_EXPANDED' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::CHECKBOXES,
                'FILTER_HINT' => 'cut diamond',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['FLUOR'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::CHECKBOXES,
                'FILTER_HINT' => 'fluor diamond',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['FLUOR_COLOR'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::CHECKBOXES,
                'FILTER_HINT' => 'fluor color diamond',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['POLISH'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::CHECKBOXES,
                'FILTER_HINT' => 'polish diamond',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['SYMMETRY'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::CHECKBOXES,
                'FILTER_HINT' => 'symmetry diamond',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['CULET'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::CHECKBOXES,
                'FILTER_HINT' => 'culet diamond',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['YEAR_MINING'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::CHECKBOXES,
                'FILTER_HINT' => 'year mining diamond',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['ORIGIN'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::CHECKBOXES,
                'FILTER_HINT' => 'origin',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['DEPTH'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::NUMBERS_WITH_SLIDER,
                'FILTER_HINT' => 'depth',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['TABLE'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::NUMBERS_WITH_SLIDER,
                'FILTER_HINT' => 'table',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['COUNT'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::NUMBERS_WITH_SLIDER,
                'FILTER_HINT' => 'count',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => 0,
                'PROPERTY_ID' => $propsList['PRICE_CARAT'],
                'SMART_FILTER' => 'Y',
                'DISPLAY_TYPE' => SectionPropertyTable::NUMBERS_WITH_SLIDER,
                'FILTER_HINT' => 'price per carat',
            ],
        ];

        foreach ($props as $prop) {
            (new \CIBlockProperty)->Update(
                $prop['PROPERTY_ID'],
                [
                    'SMART_FILTER' => 'Y',
                    'IBLOCK_ID' => $iblockId,
                ]
            );
            SectionPropertyTable::add(['fields' => $prop]);
        }

        $indexer = Manager::createIndexer($iblockId);
        if ($indexer->isExists()) {
            Manager::deleteIndex($iblockId);
        }

        $indexer->startIndex();
        $indexer->continueIndex(0);
        $indexer->endIndex();

    }

    private function deleteProps(int $iblockId)
    {
        Application::getConnection()->query("DELETE from `b_iblock_section_property` WHERE `IBLOCK_ID` = {$iblockId}");
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
        Manager::deleteIndex($iblockId);
        $this->deleteProps($iblockId);
    }
}

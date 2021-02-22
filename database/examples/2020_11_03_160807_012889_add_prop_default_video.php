<?php

use App\Models\Jewelry\JewelryBlank;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/** @noinspection PhpUnused */

class AddPropDefaultVideo20201103160807012889 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'CODE' => 'DEFAULT_VIDEO_SET',
            'NAME' => 'Номер набора видео 360 по умолчанию',
            'PROPERTY_TYPE' => 'N',
            'SORT' => '519',
            'IBLOCK_ID' => JewelryBlank::IblockID(),
            'DEFAULT_VALUE' => 1,
            'HINT' => 'Если не заполнено, то будет использован первый набор видео',
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     */
    public function down()
    {
        $codes = ['DEFAULT_VIDEO_SET'];

        $rsProperty = CIBlockProperty::GetList(
            [],
            [
                'IBLOCK_ID' => JewelryBlank::IblockID(),
            ]
        );

        while ($arProperty = $rsProperty->Fetch()) {
            if (!in_array($arProperty['CODE'], $codes, true)) {
                continue;
            }

            CIBlockProperty::Delete($arProperty['ID']);
        }
    }
}

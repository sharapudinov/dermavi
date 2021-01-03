<?php

use App\Models\Jewelry\JewelryBlank;
use App\Models\Jewelry\JewelryBlankSku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/** @noinspection PhpUnused */

class AddPropDefaultPhoto20201030144742586723 extends BitrixMigration
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
            'CODE' => 'DEFAULT_PHOTO_SET',
            'NAME' => 'Номер набора фотографий по умолчанию',
            'PROPERTY_TYPE' => 'N',
            'SORT' => '519',
            'IBLOCK_ID' => JewelryBlank::IblockID(),
            'DEFAULT_VALUE' => 1,
            'HINT' => 'Если не заполнено, то будет использован первый набор фоторафий',
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     */
    public function down()
    {
        $codes = ['DEFAULT_PHOTO_SET'];

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

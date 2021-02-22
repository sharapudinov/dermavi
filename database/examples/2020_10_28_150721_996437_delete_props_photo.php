<?php

use App\Models\Jewelry\JewelryBlankSku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/** @noinspection PhpUnused */

class DeletePropsPhoto20201028150721996437 extends BitrixMigration
{
    /**
     * @return mixed|void
     * @throws Exception
     */
    public function up()
    {
        $codes = ['PHOTO_BIG', 'PHOTO_SMALL'];

        $rsProperty = CIBlockProperty::GetList(
            [],
            [
                'IBLOCK_ID' => JewelryBlankSku::IblockID(),
            ]
        );

        while ($arProperty = $rsProperty->Fetch()) {
            if (!in_array($arProperty['CODE'], $codes, true)) {
                continue;
            }

            CIBlockProperty::Delete($arProperty['ID']);
        }
    }

    public function down()
    {
    }
}

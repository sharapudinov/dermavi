<?php

use App\Models\Jewelry\JewelryBlankSku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/** @noinspection PhpUnused */

class AddPropsPhoto20201028150721996438 extends BitrixMigration
{
    /**
     * @return mixed|void
     * @throws Exception
     */
    public function up()
    {
        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'CODE' => 'PHOTO_BIG',
            'NAME' => 'Большое фото (не используется)',
            'MULTIPLE' => 'Y',
            'SORT' => '504',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '60',
            'MULTIPLE_CNT' => '1',
            'IBLOCK_ID' => JewelryBlankSku::IblockID(),
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'CODE' => 'PHOTO_SMALL',
            'NAME' => 'Маленькое фото (заполняется автоматически)',
            'MULTIPLE' => 'Y',
            'SORT' => '505',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '60',
            'MULTIPLE_CNT' => '1',
            'IBLOCK_ID' => JewelryBlankSku::IblockID(),
        ]);
    }

    public function down()
    {
    }
}

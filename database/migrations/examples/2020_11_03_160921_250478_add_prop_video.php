<?php

use App\Models\Jewelry\JewelryBlankSku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/** @noinspection PhpUnused */
class AddPropVideo20201103160921250478 extends BitrixMigration
{
    public function up(): void
    {
        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'CODE' => 'VIDEO',
            'NAME' => 'Видео 360 (заполняется автоматически)',
            'MULTIPLE' => 'Y',
            'SORT' => '506',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '60',
            'MULTIPLE_CNT' => '1',
            'IBLOCK_ID' => JewelryBlankSku::IblockID(),
        ]);
    }

    public function down(): void
    {
    }
}

<?php

use App\Models\Jewelry\JewelryBlank;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/** @noinspection PhpUnused */

/** @noinspection PhpClassNamingConventionInspection */

class BlankAddPropEngravingDisabled20201023164248501238 extends BitrixMigration
{
    public function up(): void
    {
        (new CIBlockProperty)->Add([
            'NAME' => 'Гравировка отключена',
            'ACTIVE' => 'Y',
            'CODE' => 'ENGRAVING_DISABLED',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'SORT' => 510,
            'VALUES' => [
                [
                    'VALUE' => 'Y',
                    'DEF' => 'Y',
                ],
            ],
            'IBLOCK_ID' => JewelryBlank::iblockID(),
        ]);
    }

    public function down(): void
    {
        $property = CIBlockProperty::GetList(
            [],
            [
                'CODE' => 'ENGRAVING_DISABLED',
                'IBLOCK_ID' => JewelryBlank::iblockID(),
            ]
        )->Fetch();

        if ($property['ID']) {
            CIBlockProperty::Delete($property['ID']);
        }
    }
}

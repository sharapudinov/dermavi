<?php

use App\Models\Jewelry\Jewelry;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/** @noinspection PhpUnused */

/** @noinspection PhpClassNamingConventionInspection */

class JewelryAddPropEngravingDisabled20201023143102201898 extends BitrixMigration
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
            'IBLOCK_ID' => Jewelry::iblockID(),
        ]);
    }

    public function down(): void
    {
        $property = CIBlockProperty::GetList(
            [],
            [
                'CODE' => 'ENGRAVING_DISABLED',
                'IBLOCK_ID' => Jewelry::iblockID(),
            ]
        )->Fetch();

        if ($property['ID']) {
            CIBlockProperty::Delete($property['ID']);
        }
    }
}

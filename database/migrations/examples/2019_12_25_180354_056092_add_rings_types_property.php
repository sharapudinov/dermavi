<?php

use App\Models\Jewelry\Jewelry;
use App\Models\Jewelry\JewelrySku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;

/**
 * Класс, описывающий миграцию для добавления типа колец в ювелирное украшение
 * Class AddRingsTypesProperty20191225180354056092
 */
class AddRingsTypesProperty20191225180354056092 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new IBlockProperty())->constructDefault('TYPE', 'Тип изделия', Jewelry::iblockID())
            ->setFiltrable(true)
            ->setPropertyTypeList(
                [
                    [
                        'VALUE' => 'На помолвку',
                        'DEF' => 'N'
                    ],
                    [
                        'VALUE' => 'На свадьбу',
                        'DEF' => 'N'
                    ]
                ],
                'L'
            )
            ->add();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $property = CIBlockProperty::GetList([], ['IBLOCK_ID' => Jewelry::iblockID(), 'CODE' => 'TYPE'])->Fetch();
        CIBlockProperty::Delete($property['ID']);
    }
}

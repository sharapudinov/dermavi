<?php

use App\Helpers\LanguageHelper;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use App\Models\Jewelry\JewelryConstructorReadyProduct;

/**
 * Класс, описывающий миграцию для создания ИБ "Готовое изделие (конструктор ЮБИ)"
 * Class AddReadyJewelryProductIblock20200706115215921925
 */
class AddReadyJewelryProductIblock20200706115215921925 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlock())->Add([
            'ACTIVE' => 'Y',
            'NAME' => 'Готовые изделия (конструктор ЮБИ)',
            'CODE' => JewelryConstructorReadyProduct::IBLOCK_CODE,
            'IBLOCK_TYPE_ID' => 'catalog',
            'SITE_ID' => array_keys(LanguageHelper::getAvailableLanguages()),
            'GROUP_ID' => ['2' => 'R', '1' => 'X']
        ]);

        (new CIBlockProperty())->Add([
            'NAME' => 'Идентификатор торгового предложения оправы',
            'ACTIVE' => 'Y',
            'CODE' => 'FRAME_SKU_ID',
            'SORT' => '1',
            'PROPERTY_TYPE' => 'N',
            'IBLOCK_ID' => JewelryConstructorReadyProduct::iblockID()
        ]);

        (new CIBlockProperty())->Add([
            'NAME' => 'Идентификаторы бриллиантов',
            'ACTIVE' => 'Y',
            'MULTIPLE' => 'Y',
            'CODE' => 'DIAMONDS_ID',
            'SORT' => '2',
            'PROPERTY_TYPE' => 'N',
            'IBLOCK_ID' => JewelryConstructorReadyProduct::iblockID()
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        //
    }
}

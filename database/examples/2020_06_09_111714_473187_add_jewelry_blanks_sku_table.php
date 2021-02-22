<?php

use App\Helpers\LanguageHelper;
use App\Models\Jewelry\Dicts\JewelryFineness;
use App\Models\Jewelry\Dicts\JewelryMetal;
use App\Models\Jewelry\Dicts\JewelryMetalColor;
use App\Models\Jewelry\JewelryBlankSku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;

/**
 * Класс, описывающий миграцию для создания ИБ "Торговые предложения для заготовок ЮБИ"
 * Class AddJewelryBlanksSkuTable20200609111714473187
 */
class AddJewelryBlanksSkuTable20200609111714473187 extends BitrixMigration
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
            'NAME' => 'Торговые предложения для заготовок ЮБИ',
            'CODE' => JewelryBlankSku::IBLOCK_CODE,
            'IBLOCK_TYPE_ID' => 'catalog',
            'SITE_ID' => array_keys(LanguageHelper::getAvailableLanguages()),
            'GROUP_ID' => ['2' => 'R', '1' => 'X']
        ]);

        (new IBlockProperty())->constructDefault('METAL_COLOR_ID', 'Цвет металла', JewelryBlankSku::IblockID())
            ->setSort('500')
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryMetalColor::TABLE_CODE,
            ])
            ->add();

        (new IBlockProperty())->constructDefault('METAL_ID', 'Металл', JewelryBlankSku::IblockID())
            ->setSort('501')
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryMetal::TABLE_CODE,
            ])
            ->add();

        (new IBlockProperty())->constructDefault('FINENESS', 'Проба', JewelryBlankSku::IblockID())
            ->setSort('502')
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryFineness::TABLE_CODE,
            ])
            ->add();

        (new CIBlockProperty())->Add([
            'CODE' => 'PRODUCTION_TIME',
            'NAME' => 'Срок изготовления',
            'SORT' => '503',
            'ACTIVE' => 'Y',
            'PROPERTY_TYPE' => 'N',
            'IBLOCK_ID' => JewelryBlankSku::IblockID()
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
        CIBlock::Delete(JewelryBlankSku::IblockID());
    }
}

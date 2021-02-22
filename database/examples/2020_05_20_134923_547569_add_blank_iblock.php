<?php

use App\Helpers\SiteHelper;
use App\Models\Catalog\HL\Form;
use App\Models\Jewelry\Dicts\JewelryBlanksGroup;
use App\Models\Jewelry\Dicts\JewelryCast;
use App\Models\Jewelry\Dicts\JewelryFamily;
use App\Models\Jewelry\Dicts\JewelryFineness;
use App\Models\Jewelry\Dicts\JewelryMetal;
use App\Models\Jewelry\Dicts\JewelryMetalColor;
use App\Models\Jewelry\JewelryBlank;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;

/**
 * Класс, описывающий миграцию для создания ИБ "Заготовка ЮБИ"
 * Class AddBlankIblock20200520134923547569
 */
class AddBlankIblock20200520134923547569 extends BitrixMigration
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
            'NAME' => 'Заготовка ЮБИ',
            'CODE' => JewelryBlank::IBLOCK_CODE,
            'IBLOCK_TYPE_ID' => 'catalog',
            'SITE_ID' => SiteHelper::getSites()->pluck('LID')->toArray(),
            'GROUP_ID' => [1 => 'W', 2 => 'R']
        ]);

        (new CIBlockProperty())->Add([
            'CODE' => 'NAME_EN',
            'NAME' => 'Название (англ)',
            'SORT' => '500',
            'ACTIVE' => 'Y',
            'PROPERTY_TYPE' => 'S',
            'IBLOCK_ID' => JewelryBlank::iblockID()
        ]);

        (new CIBlockProperty())->Add([
            'CODE' => 'NAME_RU',
            'NAME' => 'Название (рус)',
            'SORT' => '501',
            'ACTIVE' => 'Y',
            'PROPERTY_TYPE' => 'S',
            'IBLOCK_ID' => JewelryBlank::iblockID()
        ]);

        (new CIBlockProperty())->Add([
            'CODE' => 'NAME_CN',
            'NAME' => 'Название (кит)',
            'SORT' => '502',
            'ACTIVE' => 'Y',
            'PROPERTY_TYPE' => 'S',
            'IBLOCK_ID' => JewelryBlank::iblockID()
        ]);

        (new CIBlockProperty())->Add([
            'CODE' => 'SLUG',
            'NAME' => 'Слаг',
            'SORT' => '503',
            'ACTIVE' => 'Y',
            'PROPERTY_TYPE' => 'S',
            'IBLOCK_ID' => JewelryBlank::iblockID()
        ]);

        (new CIBlockProperty())->Add([
            'CODE' => 'VENDOR_CODE',
            'NAME' => 'Артикул',
            'SORT' => '504',
            'ACTIVE' => 'Y',
            'PROPERTY_TYPE' => 'S',
            'IBLOCK_ID' => JewelryBlank::iblockID()
        ]);

        (new CIBlockProperty())->Add([
            'CODE' => 'WEIGHT',
            'NAME' => 'Масса',
            'SORT' => '505',
            'ACTIVE' => 'Y',
            'PROPERTY_TYPE' => 'S',
            'IBLOCK_ID' => JewelryBlank::iblockID()
        ]);

        (new CIBlockProperty())->Add([
            'CODE' => 'PRICE',
            'NAME' => 'Цена',
            'SORT' => '506',
            'ACTIVE' => 'Y',
            'PROPERTY_TYPE' => 'N',
            'IBLOCK_ID' => JewelryBlank::iblockID()
        ]);

        (new CIBlockProperty())->Add([
            'CODE' => 'RELATION_TYPE',
            'NAME' => 'Тип подбора',
            'SORT' => '507',
            'ACTIVE' => 'Y',
            'PROPERTY_TYPE' => 'L',
            'IBLOCK_ID' => JewelryBlank::iblockID(),
            'VALUES' => [
                ['VALUE' => 'По весу', 'XML_ID' => 'По весу'],
                ['VALUE' => 'По диаметру', 'XML_ID' => 'По диаметру']
            ]
        ]);

        (new CIBlockProperty())->Add([
            'CODE' => 'HAS_SIZE',
            'NAME' => 'Имеет размер',
            'SORT' => '508',
            'ACTIVE' => 'Y',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'IBLOCK_ID' => JewelryBlank::iblockID(),
            'VALUES' => [
                ['VALUE' => 'Y']
            ]
        ]);

        (new IBlockProperty())->constructDefault('BLANKS_GROUP', 'Группа заготовок', JewelryBlank::iblockID())
            ->setSort('509')
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryBlanksGroup::TABLE_CODE,
            ])
            ->add();

        (new IBlockProperty())->constructDefault('JEWELRY_TYPE', 'Тип изделия', JewelryBlank::iblockID())
            ->setSort('510')
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryFamily::TABLE_CODE,
            ])
            ->add();

        (new IBlockProperty())->constructDefault('SHAPE', 'Форма огранки', JewelryBlank::iblockID())
            ->setSort('511')
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => Form::getTableName(),
            ])
            ->add();

        (new IBlockProperty())->constructDefault('CASTS', 'Касты', JewelryBlank::iblockID())
            ->setSort('512')
            ->setMultiple(true)
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryCast::TABLE_CODE,
            ])
            ->add();

        (new IBlockProperty())->constructDefault('METAL_COLOR', 'Цвет металла', JewelryBlank::iblockID())
            ->setSort('513')
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryMetalColor::TABLE_CODE,
            ])
            ->add();

        (new IBlockProperty())->constructDefault('METAL', 'Металл', JewelryBlank::iblockID())
            ->setSort('514')
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryMetal::TABLE_CODE,
            ])
            ->add();

        (new IBlockProperty())->constructDefault('FINENESS', 'Проба', JewelryBlank::iblockID())
            ->setSort('515')
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryFineness::TABLE_CODE,
            ])
            ->add();

        (new CIBlockProperty())->Add([
            'CODE' => 'PRODUCTION_TIME',
            'NAME' => 'Срок изготовления',
            'SORT' => '516',
            'ACTIVE' => 'Y',
            'PROPERTY_TYPE' => 'N',
            'IBLOCK_ID' => JewelryBlank::iblockID()
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
        CIBlock::Delete(JewelryBlank::iblockID());
    }
}

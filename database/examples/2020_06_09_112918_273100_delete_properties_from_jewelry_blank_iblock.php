<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryFineness;
use App\Models\Jewelry\Dicts\JewelryMetal;
use App\Models\Jewelry\Dicts\JewelryMetalColor;
use App\Models\Jewelry\JewelryBlank;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для удаления определенных свойств из ИБ "Заготовки ЮБИ"
 * Class DeletePropertiesFromJewelryBlankIblock20200609112918273100
 */
class DeletePropertiesFromJewelryBlankIblock20200609112918273100 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $property = new Property(JewelryBlank::iblockID());
        $property->addPropertyToQuery('METAL_COLOR');
        $property->addPropertyToQuery('METAL');
        $property->addPropertyToQuery('FINENESS');
        $property->addPropertyToQuery('PRODUCTION_TIME');
        $properties = $property->getPropertiesInfo();

        foreach ($properties as $property) {
            CIBlockProperty::Delete($property['PROPERTY_ID']);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
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
}

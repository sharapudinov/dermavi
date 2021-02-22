<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryCollection;
use App\Models\Jewelry\Dicts\JewelrySex;
use App\Models\Jewelry\Dicts\JewelryStyle;
use App\Models\Jewelry\JewelryBlank;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;

/**
 * Класс, описывающий миграцию для обновления ИБ "Заготовка ЮБИ"
 * Class UpdateBlanksIblock20200625161936926996
 */
class UpdateBlanksIblock20200625161936926996 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new IBlockProperty())->constructDefault('COLLECTION_ID', 'Коллекция', JewelryBlank::iblockId())
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryCollection::TABLE_CODE,
            ])
            ->setSort(515)
            ->add();

        (new IBlockProperty())->constructDefault('GENDER_ID', 'Пол', JewelryBlank::iblockID())
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelrySex::TABLE_CODE
            ])
            ->setSort(516)
            ->add();

        (new IBlockProperty())->constructDefault('STYLE_ID', 'Стиль', JewelryBlank::iblockID())
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryStyle::TABLE_CODE
            ])
            ->setSort(517)
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
        $property = new Property(JewelryBlank::iblockID());
        $property->addPropertyToQuery('COLLECTION_ID');
        $property->addPropertyToQuery('GENDER_ID');
        $property->addPropertyToQuery('STYLE_ID');
        $properties = $property->getPropertiesInfo();

        foreach ($properties as $property) {
            IBlockProperty::delete($property['PROPERTY_ID']);
        }
    }
}

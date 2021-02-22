<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryType;
use App\Models\Jewelry\JewelryBlank;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;

/**
 * Класс, описывающий миграцию для добавления нового поля "Тип изделия" и изменения старого
 * Class AddJewelryTypeFieldToJewelryBlankIblock20200611121421718090
 */
class AddJewelryTypeFieldToJewelryBlankIblock20200611121421718090 extends BitrixMigration
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
        $property->addPropertyToQuery('JEWELRY_TYPE');
        $propertyId = $property->getPropertiesInfo()['JEWELRY_TYPE']['PROPERTY_ID'];

        (new IBlockProperty())
            ->constructDefault('JEWELRY_FAMILY', 'Семейство изделий', JewelryBlank::iblockID())
            ->update($propertyId);

        (new IBlockProperty())->constructDefault('JEWELRY_TYPE', 'Тип  изделия', JewelryBlank::iblockID())
            ->setSort('514')
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryType::TABLE_CODE,
            ])
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
        $property->addPropertyToQuery('JEWELRY_FAMILY');
        $property->addPropertyToQuery('JEWELRY_TYPE');
        $properties = $property->getPropertiesInfo();

        IBlockProperty::delete($properties['JEWELRY_TYPE']['PROPERTY_ID']);

        (new IBlockProperty())
            ->constructDefault('JEWELRY_TYPE', 'Тип изделия', JewelryBlank::iblockID())
            ->update($properties['JEWELRY_FAMILY']['PROPERTY_ID']);
    }
}

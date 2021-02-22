<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\JewelryBlankSku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для добавления свойств для фотографий в ИБ "Торговые предложения для заготовок ЮБИ"
 * Class AddPhotosToJewelryBlanksSkuIblock20200609130920661654
 */
class AddPhotosToJewelryBlanksSkuIblock20200609130920661654 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'CODE' => 'PHOTO_BIG',
            'NAME' => 'Большое фото',
            'MULTIPLE' => 'Y',
            'SORT' => '504',
            'IBLOCK_ID' => JewelryBlankSku::IblockID()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'CODE' => 'PHOTO_SMALL',
            'NAME' => 'Маленько фото',
            'MULTIPLE' => 'Y',
            'SORT' => '505',
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
        $property = new Property(JewelryBlankSku::IblockID());
        $property->addPropertyToQuery('PHOTO_BIG');
        $property->addPropertyToQuery('PHOTO_SMALL');
        $properties = $property->getPropertiesInfo();

        foreach ($properties as $property) {
            CIBlockProperty::Delete($property['PROPERTY_ID']);
        }
    }
}

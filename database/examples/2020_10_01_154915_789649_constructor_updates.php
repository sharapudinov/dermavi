<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\JewelryBlank;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для изменения ИБ, связанных с конструктором ЮБИ
 * Class ConstructorUpdates20201001154915789649
 */
class ConstructorUpdates20201001154915789649 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $property = (new Property(JewelryBlank::iblockID()));
        $property->addPropertyToQuery('PRICE');
        $propertyInfo = $property->getPropertiesInfo();
        (new CIBlockProperty())->Update($propertyInfo['PRICE']['PROPERTY_ID'], ['PROPERTY_TYPE' => 'N']);


    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $property = (new Property(JewelryBlank::iblockID()));
        $property->addPropertyToQuery('PRICE');
        $propertyInfo = $property->getPropertiesInfo();
        (new CIBlockProperty())->Update($propertyInfo['PRICE']['PROPERTY_ID'], ['PROPERTY_TYPE' => 'S']);
    }
}

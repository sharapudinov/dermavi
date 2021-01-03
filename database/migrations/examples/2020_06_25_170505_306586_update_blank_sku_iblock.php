<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\JewelryBlankSku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;

/**
 * Класс, описывающий миграцию для обновления ИБ "Торговые предложения для заготовок ЮБИ"
 * Class UpdateBlankSkuIblock20200625170505306586
 */
class UpdateBlankSkuIblock20200625170505306586 extends BitrixMigration
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
            'SORT' => '506',
            'CODE' => 'SIZE',
            'NAME' => 'Размер',
            'PROPERTY_TYPE' => 'N',
            'IBLOCK_ID' => JewelryBlankSku::iblockID()
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
        $property = new Property(JewelryBlankSku::iblockID());
        $property->addPropertyToQuery('SIZE');
        $propertyInfo = $property->getPropertiesInfo()['SIZE'];
        IBlockProperty::delete($propertyInfo['PROPERTY_ID']);
    }
}

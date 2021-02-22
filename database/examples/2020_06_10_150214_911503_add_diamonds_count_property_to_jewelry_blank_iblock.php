<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\JewelryBlank;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для добавления свойства в ИБ "Заготовки ЮБИ"
 * Class AddDiamondsCountPropertyToJewelryBlankIblock20200610150214911503
 */
class AddDiamondsCountPropertyToJewelryBlankIblock20200610150214911503 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const CODE = 'DIAMONDS_COUNT';

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
            'PROPERTY_TYPE' => 'N',
            'SORT' => '513',
            'CODE' => self::CODE,
            'NAME' => 'Количество бриллиантов',
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
        $property = new Property(JewelryBlank::iblockID());
        $property->addPropertyToQuery(self::CODE);
        $propertyInfo = $property->getPropertiesInfo()[self::CODE];
        CIBlockProperty::Delete($propertyInfo['PROPERTY_ID']);
    }
}

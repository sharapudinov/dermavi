<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Jewelry;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для создания свойства "Показывать в рекоммендациях" для ИБ "Ювелирное изделие"
 * Class AddShowOnIndexPropertyToJewelryIblock20200528163730793743
 */
class AddShowOnIndexPropertyToJewelryIblock20200528163730793743 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY = 'IS_RECOMMENDED';

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
            'CODE' => self::PROPERTY,
            'NAME' => 'Показывать в рекоммендациях',
            'IBLOCK_ID' => Jewelry::iblockID(),
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'VALUES' => [
                ['VALUE' => 'Y']
            ]
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
        $property = new Property(Jewelry::iblockID());
        $property->addPropertyToQuery(self::PROPERTY);
        $propertyInfo = $property->getPropertiesInfo()[self::PROPERTY];
        CIBlockProperty::Delete($propertyInfo['PROPERTY_ID']);
    }
}

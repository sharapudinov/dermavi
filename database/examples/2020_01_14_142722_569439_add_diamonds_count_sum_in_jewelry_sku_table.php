<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\JewelrySku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для добавления свойства "Количество бриллиантов-вставок" в JewelrySku
 * Class AddDiamondsCountSumInJewelrySkuTable20200114142722569439
 */
class AddDiamondsCountSumInJewelrySkuTable20200114142722569439 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'DIAMONDS_COUNT';

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
            'CODE' => self::PROPERTY_CODE,
            'NAME' => 'Сумма бриллиантов-вставок',
            'PROPERTY_TYPE' => 'N',
            'IBLOCK_ID' => JewelrySku::iblockID()
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
        $property = new Property(JewelrySku::iblockID());
        $property->addPropertyToQuery(self::PROPERTY_CODE);
        CIBlockProperty::Delete($property->getPropertiesInfo()[self::PROPERTY_CODE]['PROPERTY_ID']);
    }
}

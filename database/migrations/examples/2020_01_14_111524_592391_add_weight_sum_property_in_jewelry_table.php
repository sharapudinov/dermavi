<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\JewelrySku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для создания свойства "Сумма карат вставок" в ИБ "Ювелирные изделия"
 * Class AddWeightSumPropertyInJewelryTable20200114111524592391
 */
class AddWeightSumPropertyInJewelryTable20200114111524592391 extends BitrixMigration
{
    /** @var string Символьный код нового свойства */
    private const PROPERTY_CODE = 'WEIGHT_SUM';

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
            'NAME' => 'Сумма весов вставок (карат)',
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

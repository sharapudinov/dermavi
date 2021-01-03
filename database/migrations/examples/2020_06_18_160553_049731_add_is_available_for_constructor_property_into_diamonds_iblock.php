<?php

use App\Core\BitrixProperty\Property;
use App\Models\Catalog\Diamond;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для добавления свойства "Доступен для конструктора" в ИБ "Бриллианты"
 * Class AddIsAvailableForConstructorPropertyIntoDiamondsIblock20200618160553049731
 */
class AddIsAvailableForConstructorPropertyIntoDiamondsIblock20200618160553049731 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'CAN_CONSTRUCTOR';

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
            'NAME' => 'Доступен для конструктора',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'IBLOCK_ID' => Diamond::iblockID(),
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
        $property = new Property(Diamond::iblockID());
        $property->addPropertyToQuery(self::PROPERTY_CODE);
        $propertyInfo = $property->getPropertiesInfo()[self::PROPERTY_CODE];
        CIBlockProperty::Delete($propertyInfo['PROPERTY_ID']);
    }
}

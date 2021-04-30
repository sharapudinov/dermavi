<?php

use App\Core\BitrixProperty\Property;
use App\Models\Catalog\Catalog;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для удаления ненужных более свойств в ИБ "Бриллианты"
 * Class RemoveUnnecessaryDiamondProperties20190902115123049968
 */
class RemoveUnnecessaryDiamondProperties20190902115123049968 extends BitrixMigration
{
    /** @var array|array[] $unnecessaryProperties - Массив, описывающий свойста инфоблока для удаления */
    private $unnecessaryProperties;

    /**
     * AddSectionsIntoDiamondsIblock20190902083156281082 constructor.
     */
    public function __construct()
    {
        $this->unnecessaryProperties = [
            [
                'NAME' => 'Цена для физ. лиц',
                'CODE' => 'PUBLIC_PRICE',
                'PROPERTY_TYPE' => 'N',
                'IBLOCK_ID' => Diamond::iblockID()
            ],
            [
                'NAME' => 'Товар для физ лиц',
                'CODE' => 'IS_FOR_PHYSICAL',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'C',
                'IBLOCK_ID' => Diamond::iblockID(),
                'VALUES' => [
                    ['VALUE' => 'Y']
                ]
            ],
            [
                'NAME' => 'Аукционный товар',
                'CODE' => 'IS_AUCTION_PRODUCT',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'C',
                'IBLOCK_ID' => Diamond::iblockID(),
                'VALUES' => [
                    ['VALUE' => 'Y']
                ]
            ]
        ];

        parent::__construct();
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        return;
        /** @var Property $property - Экземпляр класса для работы со свойствами */
        $property = new Property(Diamond::iblockID());
        foreach ($this->unnecessaryProperties as $unnecessaryProperty) {
            $property->addPropertyToQuery($unnecessaryProperty['CODE']);
        }

        /** @var array|array[] $propertiesInfo - Массив, описывающий запрошенные свойства ИБ */
        $propertiesInfo = $property->getPropertiesInfo();
        foreach ($propertiesInfo as $propertyInfo) {
            CIBlockProperty::Delete($propertyInfo['PROPERTY_ID']);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        foreach ($this->unnecessaryProperties as $unnecessaryProperty) {
            (new CIBlockProperty)->Add($unnecessaryProperty);
        }
    }
}

<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryType;
use App\Models\Jewelry\Jewelry;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для изменения свойства "Тип изделия" со списка на связь с highloadblock
 * Class MoveJewelryTypeToHighloadblock20200327105659910367
 */
class MoveJewelryTypeToHighloadblock20200327105659910367 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlockProperty())->Add(
            [
                'CODE' => 'JEWELRY_TYPE',
                'NAME' => 'Тип изделия',
                'IBLOCK_ID' => Jewelry::iblockID(),
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'directory',
                'USER_TYPE_SETTINGS' => [
                    'size' => 1,
                    'width' => 0,
                    'group' => 'N',
                    'multiple' => 'N',
                    'TABLE_NAME' => JewelryType::TABLE_CODE
                ]
            ]
        );

        JewelryType::create([
            'UF_XML_ID' => 'engagement',
            'UF_NAME' => 'На помолвку',
            'UF_SORT' => '1',
            'UF_NAME_EN' => 'На помолвку',
            'UF_NAME_RU' => 'На помолвку',
            'UF_NAME_CN' => 'На помолвку'
        ]);

        JewelryType::create([
            'UF_XML_ID' => 'wedding',
            'UF_NAME' => 'На свадьбу',
            'UF_SORT' => '2',
            'UF_NAME_EN' => 'На свадьбу',
            'UF_NAME_RU' => 'На свадьбу',
            'UF_NAME_CN' => 'На свадьбу'
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
        $property->addPropertyToQuery('JEWELRY_TYPE');
        $propertyInfo = $property->getPropertiesInfo()['JEWELRY_TYPE'];

        CIBlockProperty::Delete($propertyInfo['PROPERTY_ID']);
        $types = JewelryType::getList();
        foreach ($types as $type) {
            $type->delete();
        }
    }
}

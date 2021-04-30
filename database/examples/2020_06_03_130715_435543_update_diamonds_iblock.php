<?php

use App\Core\BitrixProperty\Property;
use App\Models\Catalog\Catalog;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для обновления ИБ "Бриллианты"
 * Class UpdateDiamondsIblock20200603130715435543
 */
class UpdateDiamondsIblock20200603130715435543 extends BitrixMigration
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
            'SORT' => '701',
            'CODE' => 'DIAMETER',
            'NAME' => 'Значение диаметра для круглых, длина для фантазийных форм огранки',
            'IBLOCK_ID' => Diamond::iblockID()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '702',
            'CODE' => 'WIDTH',
            'NAME' => 'Значение ширины для фантазийных форм огранки',
            'IBLOCK_ID' => Diamond::iblockID()
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
        $property->addPropertyToQuery('DIAMETER');
        $property->addPropertyToQuery('WIDTH');
        $properties = $property->getPropertiesInfo();

        foreach ($properties as $property) {
            CIBlockProperty::Delete($property['PROPERTY_ID']);
        }
    }
}

<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryAssistantStyle;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для удаления старого свойства "Текст" в хлблоке "Стиль (помощник по стилю)"
 * Class DeleteTextPropertyInTheStyleAssistantTable20200810145510612703
 */
class DeleteTextPropertyInTheStyleAssistantTable20200810145510612703 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $property = Property::getUserFields(JewelryAssistantStyle::TABLE_CODE, ['UF_TEXT'])[0];
        UserField::delete($property['ID']);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        //
    }
}

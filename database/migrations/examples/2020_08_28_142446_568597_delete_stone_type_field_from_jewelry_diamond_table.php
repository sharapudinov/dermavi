<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\JewelryDiamond;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для удаления поля "Тип камня" из таблицы "Бриллиант-вставка"
 * Class DeleteStoneTypeFieldFromJewelryDiamondTable20200828142446568597
 */
class DeleteStoneTypeFieldFromJewelryDiamondTable20200828142446568597 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $property = Property::getUserFields(JewelryDiamond::TABLE_CODE, ['UF_TYPE'])[0];
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

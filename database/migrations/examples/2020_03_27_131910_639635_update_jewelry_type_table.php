<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryType;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления полей в таблице "Тип ювелирного изделия"
 * Class UpdateJewelryTypeTable20200327131910639635
 */
class UpdateJewelryTypeTable20200327131910639635 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'UF_SHOW_ON_INDEX';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entityId = 'HLBLOCK_' . highloadblock(JewelryType::TABLE_CODE)['ID'];
        (new UserField())->constructDefault($entityId, self::PROPERTY_CODE)
            ->setXmlId(self::PROPERTY_CODE)
            ->setUserType('boolean')
            ->setLangDefault('ru', 'Показывать на главной')
            ->setLangDefault('en', 'Show on index')
            ->setLangDefault('cn', 'Show on index')
            ->add();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $property = Property::getUserFields(JewelryType::TABLE_CODE, [self::PROPERTY_CODE])[0];
        UserField::delete($property['ID']);
    }
}

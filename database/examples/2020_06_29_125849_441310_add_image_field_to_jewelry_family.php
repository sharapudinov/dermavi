<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryFamily;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления поля "Изображение" в таблицу "Семейство изделий"
 * Class AddImageFieldToJewelryFamily20200629125849441310
 */
class AddImageFieldToJewelryFamily20200629125849441310 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'UF_IMAGE';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entity = 'HLBLOCK_' . highloadblock(JewelryFamily::TABLE_CODE)['ID'];
        (new UserField())->constructDefault($entity, self::PROPERTY_CODE)
            ->setXmlId(self::PROPERTY_CODE)
            ->setUserType('file')
            ->setLangDefault('ru', 'Изображение')
            ->setLangDefault('en', 'Изображение')
            ->setLangDefault('cn', 'Изображение')
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
        $property = Property::getUserFields(JewelryFamily::TABLE_CODE, [self::PROPERTY_CODE])[0];
        UserField::delete($property['ID']);
    }
}

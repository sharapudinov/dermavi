<?php

use App\Core\BitrixProperty\Property;
use App\Models\Catalog\HL\StoneType;
use App\Models\Jewelry\JewelryDiamond;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания поля "Тип камня" в таблице "Бриллианты-вставки"
 * Class AddStoneTypeFieldToJewelryDiamondTable20200828151133073534
 */
class AddStoneTypeFieldToJewelryDiamondTable20200828151133073534 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY = 'UF_TYPE';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entity = 'HLBLOCK_' . highloadblock(JewelryDiamond::TABLE_CODE)['ID'];
        (new UserField())->constructDefault($entity, self::PROPERTY)
            ->setXmlId(self::PROPERTY)
            ->setUserType('hlblock')
            ->setUserTypeHL(StoneType::TABLE_CODE, 'UF_XML_ID')
            ->setLangDefault('ru', 'Тип камня')
            ->setLangDefault('en', 'Тип камня')
            ->setLangDefault('cn', 'Тип камня')
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
        $property = Property::getUserFields(JewelryDiamond::TABLE_CODE, [self::PROPERTY])[0];
        UserField::delete($property['ID']);
    }
}

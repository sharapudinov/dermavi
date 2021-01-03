<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryAssistantStyle;
use App\Models\Jewelry\Dicts\JewelryCollection;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления поля "Коллекции" в таблице "Стиль (помощник по стилю)"
 * Class AddCollectionFieldToJewelryAssistantStyleTable20200630124005858803
 */
class AddCollectionFieldToJewelryAssistantStyleTable20200630124005858803 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY = 'UF_COLLECTIONS';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entity = 'HLBLOCK_' . highloadblock(JewelryAssistantStyle::TABLE_CODE)['ID'];
        (new UserField())->constructDefault($entity, self::PROPERTY)
            ->setXmlId(self::PROPERTY)
            ->setMultiple(true)
            ->setUserType('directory')
            ->setUserTypeHL(JewelryCollection::TABLE_CODE, 'UF_XML_ID')
            ->setSettings(['LIST_HEIGHT' => 5])
            ->setLangDefault('ru', 'Коллекции')
            ->setLangDefault('en', 'Коллекции')
            ->setLangDefault('cn', 'Коллекции')
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
        $property = Property::getUserFields(JewelryAssistantStyle::TABLE_CODE, [self::PROPERTY])[0];
        UserField::delete($property['ID']);
    }
}

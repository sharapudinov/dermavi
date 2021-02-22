<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryFamily;
use App\Models\Jewelry\Dicts\JewelryType;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления свойства "Тип издлия" в таблицу "Семейство изделий"
 * Class AddJewelryTypeFieldToJewelryFamilyTable20200611111136309794
 */
class AddJewelryTypeFieldToJewelryFamilyTable20200611111136309794 extends BitrixMigration
{
    /** @var string Символьный код свойства "Тип изделия" */
    private const TYPE_CODE = 'UF_TYPES';

    /** @var string Символьный код свойства "Код" */
    private const SYM_CODE = 'UF_CODE';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entity = 'HLBLOCK_' . highloadblock(JewelryFamily::TABLE_CODE)['ID'];

        (new UserField())->constructDefault($entity, self::SYM_CODE)
            ->setXmlId(self::SYM_CODE)
            ->setLangDefault('ru', 'Символьный код')
            ->setLangDefault('en', 'Символьный код')
            ->setLangDefault('cn', 'Символьный код')
            ->add();

        (new UserField())->constructDefault($entity, self::TYPE_CODE)
            ->setXmlId(self::TYPE_CODE)
            ->setMultiple(true)
            ->setSettings(['LIST_HEIGHT' => 5])
            ->setUserType('directory')
            ->setUserTypeHL(JewelryType::TABLE_CODE, 'UF_XML_ID')
            ->setLangDefault('ru', 'Тип изделия')
            ->setLangDefault('en', 'Тип изделия')
            ->setLangDefault('cn', 'Тип изделия')
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
        $properties = Property::getUserFields(JewelryFamily::TABLE_CODE, [self::TYPE_CODE, self::SYM_CODE]);
        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }
    }
}

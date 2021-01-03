<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryAssistantStyle;
use App\Models\Jewelry\Dicts\JewelrySex;
use App\Models\Jewelry\Dicts\JewelryStyle;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Стиль (помощник по стилю)"
 * Class AddJewelryAssistantTable20200625160836727770
 */
class AddJewelryAssistantTable20200625160836727770 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $hblockId = (new HighloadBlock())
            ->constructDefault('JewelryAssistantStyle', JewelryAssistantStyle::TABLE_CODE)
            ->setLang('ru', 'Стиль (помощник по стилю)')
            ->setLang('en', 'JewelryAssistantStyle')
            ->setLang('cn', 'JewelryAssistantStyle')
            ->add();

        $entityId = 'HLBLOCK_' . $hblockId;

        (new UserField())->constructDefault($entityId, 'UF_XML_ID')
            ->setXmlId('UF_XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Внешний код')
            ->setLangDefault('en', 'Xml id')
            ->setLangDefault('cn', 'Xml id')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_SORT')
            ->setXmlId('UF_SORT')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Сортировка')
            ->setLangDefault('en', 'Сортировка')
            ->setLangDefault('cn', 'Сортировка')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_NAME_RU')
            ->setXmlId('UF_NAME_RU')
            ->setLangDefault('ru', 'Название (рус)')
            ->setLangDefault('en', 'Название (рус)')
            ->setLangDefault('cn', 'Название (рус)')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_NAME_EN')
            ->setXmlId('UF_NAME_EN')
            ->setLangDefault('ru', 'Название (англ)')
            ->setLangDefault('en', 'Название (англ)')
            ->setLangDefault('cn', 'Название (англ)')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_NAME_CN')
            ->setXmlId('UF_NAME_CN')
            ->setLangDefault('ru', 'Название (кит)')
            ->setLangDefault('en', 'Название (кит)')
            ->setLangDefault('cn', 'Название (кит)')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_GENDER')
            ->setXmlId('UF_GENDER')
            ->setUserType('hlblock')
            ->setUserTypeHL(JewelrySex::TABLE_CODE, 'UF_NAME')
            ->setLangDefault('ru', 'Для кого')
            ->setLangDefault('en', 'Для кого')
            ->setLangDefault('cn', 'Для кого')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_IMAGE')
            ->setXmlId('UF_IMAGE')
            ->setUserType('file')
            ->setLangDefault('ru', 'Изображение')
            ->setLangDefault('en', 'Изображение')
            ->setLangDefault('cn', 'Изображение')
            ->add();

        db()->query('TRUNCATE ' . JewelryStyle::TABLE_CODE);
        $properties = Property::getUserFields(JewelryStyle::TABLE_CODE, ['UF_GENDER', 'UF_IMAGE']);
        foreach ($properties as $property) {
            UserField::delete($property['ID']);
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
        //
    }
}

<?php

use App\Models\Jewelry\Dicts\JewelryBlanksGroup;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Группа заготовок ЮБИ"
 * Class AddBlanksGroupTable20200520125254931914
 */
class AddBlanksGroupTable20200520125254931914 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $hlBlockId = (new HighloadBlock())
            ->constructDefault('JewelryBlanksGroup', JewelryBlanksGroup::TABLE_CODE)
            ->setLang('ru', 'Группа заготовок ЮБИ')
            ->setLang('en', 'Jewelry blanks group')
            ->setLang('cn', 'Jewelry blanks group')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_XML_ID')
            ->setXmlId('UF_XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Внешний код')
            ->setLangDefault('en', 'Внешний код')
            ->setLangDefault('cn', 'Внешний код')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_NAME_EN')
            ->setXmlId('UF_NAME_EN')
            ->setLangDefault('ru', 'Название (англ)')
            ->setLangDefault('en', 'Название (англ)')
            ->setLangDefault('cn', 'Название (англ)')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_NAME_RU')
            ->setXmlId('UF_NAME_RU')
            ->setLangDefault('ru', 'Название (рус)')
            ->setLangDefault('en', 'Название (рус)')
            ->setLangDefault('cn', 'Название (рус)')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_NAME_CN')
            ->setXmlId('UF_NAME_CN')
            ->setLangDefault('ru', 'Название (кит)')
            ->setLangDefault('en', 'Название (кит)')
            ->setLangDefault('cn', 'Название (кит)')
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
        HighloadBlock::delete(JewelryBlanksGroup::TABLE_CODE);
    }
}

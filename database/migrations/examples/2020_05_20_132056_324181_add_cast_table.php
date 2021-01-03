<?php

use App\Models\Jewelry\Dicts\JewelryCast;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Каст ЮБИ"
 * Class AddCastTable20200520132056324181
 */
class AddCastTable20200520132056324181 extends BitrixMigration
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
            ->constructDefault('JewelryCast', JewelryCast::TABLE_CODE)
            ->setLang('ru', 'Каст ЮБИ')
            ->setLang('en', 'Jewelry cast')
            ->setLang('cn', 'Jewelry cast')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_XML_ID')
            ->setXmlId('UF_XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Внешний код')
            ->setLangDefault('en', 'Внешний код')
            ->setLangDefault('cn', 'Внешний код')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_FROM')
            ->setXmlId('UF_FROM')
            ->setLangDefault('ru', 'Нижняя граница')
            ->setLangDefault('en', 'Нижняя граница')
            ->setLangDefault('cn', 'Нижняя граница')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_TO')
            ->setXmlId('UF_TO')
            ->setLangDefault('ru', 'Верхняя граница')
            ->setLangDefault('en', 'Верхняя граница')
            ->setLangDefault('cn', 'Верхняя граница')
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
        HighloadBlock::delete(JewelryCast::TABLE_CODE);
    }
}

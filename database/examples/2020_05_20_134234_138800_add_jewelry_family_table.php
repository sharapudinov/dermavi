<?php

use App\Models\Jewelry\Dicts\JewelryFamily;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Семейство изделий"
 * Class AddJewelryFamilyTable20200520134234138800
 */
class AddJewelryFamilyTable20200520134234138800 extends BitrixMigration
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
            ->constructDefault('JewelryFamily', JewelryFamily::TABLE_CODE)
            ->setLang('ru', 'Семейство изделий')
            ->setLang('en', 'Jewelry family')
            ->setLang('cn', 'Jewelry family')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_XML_ID')
            ->setXmlId('UF_XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Внешний код')
            ->setLangDefault('en', 'Внешний код')
            ->setLangDefault('cn', 'Внешний код')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_NAME')
            ->setXmlId('UF_NAME')
            ->setLangDefault('ru', 'Название')
            ->setLangDefault('en', 'Название')
            ->setLangDefault('cn', 'Название')
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
        HighloadBlock::delete(JewelryFamily::TABLE_CODE);
    }
}

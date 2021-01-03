<?php

use App\Models\Catalog\HL\StoneType;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Тип камня"
 * Class CreateStoneTypeTable20200828145654192850
 */
class CreateStoneTypeTable20200828145654192850 extends BitrixMigration
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
            ->constructDefault('StoneType', StoneType::TABLE_CODE)
            ->setLang('ru', 'Тип камня')
            ->setLang('en', 'Тип камня')
            ->setLang('cn', 'Тип камня')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_XML_ID')
            ->setXmlId('UF_NAME')
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
        HighloadBlock::delete(StoneType::TABLE_CODE);
    }
}

<?php

use App\Models\HL\SalutationType;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Обращения"
 * Class AddSalutationTypeTable20200318133321074258
 */
class AddSalutationTypeTable20200318133321074258 extends BitrixMigration
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
            ->constructDefault('SalutationType', SalutationType::TABLE_CODE)
            ->setLang('ru', 'Обращения')
            ->setLang('en', 'SalutationTypes')
            ->setLang('cn', 'SalutationTypes')
            ->add();

        $entityId = 'HLBLOCK_' . $hblockId;

        (new UserField())->constructDefault($entityId, 'UF_XML_ID')
            ->setXmlId('UF_XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Внешний код')
            ->setLangDefault('en', 'Xml id')
            ->setLangDefault('cn', 'Xml id')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_VALUE')
            ->setXmlId('UF_VALUE')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Значение')
            ->setLangDefault('en', 'Value')
            ->setLangDefault('cn', 'Value')
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
        HighloadBlock::delete(SalutationType::TABLE_CODE);
    }
}

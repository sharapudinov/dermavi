<?php

use App\Models\Catalog\HL\FormsAccordanceGroup;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Группы соответствий форм"
 * Class AddFormsAccordancesGroupsTable20200828111348654987
 */
class AddFormsAccordancesGroupsTable20200828111348654987 extends BitrixMigration
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
            ->constructDefault('FormsAccordancesGroups', FormsAccordanceGroup::TABLE_CODE)
            ->setLang('ru', 'Группы соответствий форм')
            ->setLang('en', 'Группы соответствий форм')
            ->setLang('cn', 'Группы соответствий форм')
            ->add();

        $entityId = 'HLBLOCK_' . $hblockId;

        (new UserField())->constructDefault($entityId, 'UF_NAME')
            ->setXmlId('UF_NAME')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Название группы')
            ->setLangDefault('en', 'Название группы')
            ->setLangDefault('cn', 'Название группы')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_FORMS_ID')
            ->setXmlId('UF_FORMS_ID')
            ->setMandatory(true)
            ->setMultiple(true)
            ->setLangDefault('ru', 'Идентификаторы форм')
            ->setLangDefault('en', 'Идентификаторы форм')
            ->setLangDefault('cn', 'Идентификаторы форм')
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
        HighloadBlock::delete(FormsAccordanceGroup::TABLE_CODE);
    }
}

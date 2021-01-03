<?php

use App\Models\HL\Signatory;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Подписант"
 * Class CreateSignatoryTable20191118162001667482
 */
class CreateSignatoryTable20191118162001667482 extends BitrixMigration
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
            ->constructDefault('Signatory', Signatory::TABLE_CODE)
            ->setLang('ru', 'Подписант')
            ->setLang('en', 'Signatory')
            ->setLang('cn', 'Signatory')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;
        (new UserField())->constructDefault($entityId, 'UF_NAME')
            ->setXmlId('UF_NAME')
            ->setLangDefault('ru', 'Имя')
            ->setLangDefault('en', 'Name')
            ->setLangDefault('cn', 'Name')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_SURNAME')
            ->setXmlId('UF_SURNAME')
            ->setLangDefault('ru', 'Фамилия')
            ->setLangDefault('en', 'Surname')
            ->setLangDefault('cn', 'Surname')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_MIDDLE_NAME')
            ->setXmlId('UF_MIDDLE_NAME')
            ->setLangDefault('ru', 'Отчество')
            ->setLangDefault('en', 'Middle name')
            ->setLangDefault('cn', 'Middle name')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_CITIZENSHIP')
            ->setXmlId('UF_CITIZENSHIP')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Гражданство')
            ->setLangDefault('en', 'Citizenship')
            ->setLangDefault('cn', 'Citizenship')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_BIRTH_COUNTRY')
            ->setXmlId('UF_BIRTH_COUNTRY')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Страна рождения')
            ->setLangDefault('en', 'Birth country')
            ->setLangDefault('cn', 'Birth country')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_BIRTH_PLACE')
            ->setXmlId('UF_BIRTH_PLACE')
            ->setLangDefault('ru', 'Место рождения')
            ->setLangDefault('en', 'Birth place')
            ->setLangDefault('cn', 'Birth place')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_BIRTHDAY')
            ->setXmlId('UF_BIRTHDAY')
            ->setUserType('date')
            ->setLangDefault('ru', 'Дата рождения')
            ->setLangDefault('en', 'Birthday')
            ->setLangDefault('cn', 'Birthday')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_TAX_NUMBER')
            ->setXmlId('UF_TAX_NUMBER')
            ->setLangDefault('ru', 'ИНН')
            ->setLangDefault('en', 'Tax number')
            ->setLangDefault('cn', 'Tax number')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_PASSPORT_DATA_ID')
            ->setXmlId('UF_PASSPORT_DATA_ID')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Паспортные данные')
            ->setLangDefault('en', 'Passport data id')
            ->setLangDefault('cn', 'Tax number')
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
        HighloadBlock::delete(Signatory::TABLE_CODE);
    }
}

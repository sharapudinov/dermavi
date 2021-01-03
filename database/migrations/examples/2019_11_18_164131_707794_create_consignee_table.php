<?php

use App\Models\HL\Consignee;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Грузополучатель"
 * Class CreateConsigneeTable20191118164131707794
 */
class CreateConsigneeTable20191118164131707794 extends BitrixMigration
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
            ->constructDefault('Consignee', Consignee::TABLE_CODE)
            ->setLang('ru', 'Грузополучатель')
            ->setLang('en', 'Consignee')
            ->setLang('cn', 'Consignee')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;
        (new UserField())->constructDefault($entityId, 'UF_PERSON_TYPE_ID')
            ->setXmlId('UF_PERSON_TYPE_ID')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Тип лица')
            ->setLangDefault('en', 'Person type')
            ->setLangDefault('cn', 'Person type')
            ->add();

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
            ->setLangDefault('ru', 'Паспортные данные')
            ->setLangDefault('en', 'Passport data')
            ->setLangDefault('cn', 'Passport data')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_PUBLIC_OFFICIAL')
            ->setXmlId('UF_PUBLIC_OFFICIAL')
            ->setLangDefault('ru', 'Публичное должностное лицо')
            ->setLangDefault('en', 'Public official')
            ->setLangDefault('cn', 'Public official')
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
        HighloadBlock::delete(Consignee::TABLE_CODE);
    }
}

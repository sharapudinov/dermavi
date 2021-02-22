<?php

use App\Models\HL\Company\SingleExecutiveAuthority;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Единоличный исполнительный Орган"
 * Class CreateSignleExecutiveAuthorityTable20191118163146791454
 */
class CreateSignleExecutiveAuthorityTable20191118163146791454 extends BitrixMigration
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
            ->constructDefault('SingleExecutiveAuthority', SingleExecutiveAuthority::TABLE_CODE)
            ->setLang('ru', 'Единоличный исполнительный орган')
            ->setLang('en', 'Single Executive Authority')
            ->setLang('cn', 'Single Executive Authority')
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

        (new UserField())->constructDefault($entityId, 'UF_PASSPORT_DATA_ID')
            ->setXmlId('UF_PASSPORT_DATA_ID')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Паспортные данные')
            ->setLangDefault('en', 'Passport data id')
            ->setLangDefault('cn', 'Tax number')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_DOCUMENTS')
            ->setXmlId('UF_DOCUMENTS')
            ->setLangDefault('ru', 'Документы')
            ->setLangDefault('en', 'Documents')
            ->setLangDefault('cn', 'Documents')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_PUBLIC_OFFICIAL')
            ->setXmlId('UF_PUBLIC_OFFICIAL')
            ->setUserType('integer')
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
        HighloadBlock::delete(SingleExecutiveAuthority::TABLE_CODE);
    }
}

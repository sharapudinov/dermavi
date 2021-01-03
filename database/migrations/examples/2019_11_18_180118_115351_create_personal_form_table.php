<?php

use App\Models\HL\PersonalForm;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Личная анкета"
 * Class CreatePersonalFormTable20191118180118115351
 */
class CreatePersonalFormTable20191118180118115351 extends BitrixMigration
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
            ->constructDefault('PersonalForm', PersonalForm::TABLE_CODE)
            ->setLang('ru', 'Личная анкета')
            ->setLang('en', 'Personal form')
            ->setLang('cn', 'Personal form')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;
        (new UserField())->constructDefault($entityId, 'UF_USER_ID')
            ->setXmlId('UF_USER_ID')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Пользователь')
            ->setLangDefault('en', 'User')
            ->setLangDefault('cn', 'User')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_PUBLIC_OFFICIAL')
            ->setXmlId('UF_PUBLIC_OFFICIAL')
            ->setLangDefault('ru', 'Общественное должностное лицо')
            ->setLangDefault('en', 'Public official')
            ->setLangDefault('cn', 'Public official')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_SIGNATORIES_IDS')
            ->setXmlId('UF_SIGNATORIES_IDS')
            ->setMultiple(true)
            ->setLangDefault('ru', 'Подписанты')
            ->setLangDefault('en', 'Signatories')
            ->setLangDefault('cn', 'Signatories')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_INTEREST')
            ->setXmlId('UF_INTEREST')
            ->setLangDefault('ru', 'Интерес')
            ->setLangDefault('en', 'Interest')
            ->setLangDefault('cn', 'Interest')
            ->add();

        db()->query('ALTER TABLE ' . PersonalForm::TABLE_CODE . ' MODIFY COLUMN UF_INTEREST TEXT');

        (new UserField())->constructDefault($entityId, 'UF_RELATIONSHIP_DESC')
            ->setXmlId('UF_RELATIONSHIP_DESC')
            ->setLangDefault('ru', 'Характер деловых отношений')
            ->setLangDefault('en', 'Relationship character')
            ->setLangDefault('cn', 'Relationship character')
            ->add();

        db()->query('ALTER TABLE ' . PersonalForm::TABLE_CODE . ' MODIFY COLUMN UF_RELATIONSHIP_DESC TEXT');

        (new UserField())->constructDefault($entityId, 'UF_DECLARATIONS')
            ->setXmlId('UF_DECLARATIONS')
            ->setLangDefault('ru', 'Декларации')
            ->setLangDefault('en', 'Declarations')
            ->setLangDefault('cn', 'Declarations')
            ->add();

        db()->query('ALTER TABLE ' . PersonalForm::TABLE_CODE . ' MODIFY COLUMN UF_DECLARATIONS TEXT');

        (new UserField())->constructDefault($entityId, 'UF_SEA_ID')
            ->setXmlId('UF_SEA_ID')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Единоличный исполнительный орган')
            ->setLangDefault('en', 'Single executive authority')
            ->setLangDefault('cn', 'Single executive authority')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_CONSIGNEES_IDS')
            ->setXmlId('UF_CONSIGNEES_IDS')
            ->setMultiple(true)
            ->setUserType('integer')
            ->setLangDefault('ru', 'Грузополучатели')
            ->setLangDefault('en', 'Consignees')
            ->setLangDefault('cn', 'Consignees')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_BENEFICIARIES_IDS')
            ->setXmlId('UF_BENEFICIARIES_IDS')
            ->setMultiple(true)
            ->setUserType('integer')
            ->setLangDefault('ru', 'Бенефициары')
            ->setLangDefault('en', 'Beneficiaries')
            ->setLangDefault('cn', 'Beneficiaries')
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
        HighloadBlock::delete(PersonalForm::TABLE_CODE);
    }
}

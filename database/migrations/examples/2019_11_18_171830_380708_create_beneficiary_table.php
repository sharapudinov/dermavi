<?php

use App\Models\HL\Beneficiary;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Бенефициар"
 * Class CreateBeneficiaryTable20191118171830380708
 */
class CreateBeneficiaryTable20191118171830380708 extends BitrixMigration
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
            ->constructDefault('Beneficiary', Beneficiary::TABLE_CODE)
            ->setLang('ru', 'Бенефициар')
            ->setLang('en', 'Beneficiary')
            ->setLang('cn', 'Beneficiary')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;
        (new UserField())->constructDefault($entityId, 'UF_PERSON_TYPE_ID')
            ->setXmlId('UF_PERSON_TYPE_ID')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Тип лица')
            ->setLangDefault('en', 'Person type')
            ->setLangDefault('cn', 'Person type')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_SHARE')
            ->setXmlId('UF_SHARE')
            ->setLangDefault('ru', 'Доля участия')
            ->setLangDefault('en', 'Share')
            ->setLangDefault('cn', 'Share')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_FIELDS')
            ->setXmlId('UF_FIELDS')
            ->setUserType('string')
            ->setLangDefault('ru', 'Поля')
            ->setLangDefault('en', 'Fields')
            ->setLangDefault('cn', 'Fields')
            ->add();

        db()->query('ALTER TABLE ' . Beneficiary::TABLE_CODE . ' MODIFY COLUMN UF_FIELDS TEXT');

        (new UserField())->constructDefault($entityId, 'UF_PARENT_ID')
            ->setXmlId('UF_PARENT_ID')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Родительский бенефициар')
            ->setLangDefault('en', 'Parent beneficiary')
            ->setLangDefault('cn', 'Parent beneficiary')
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
        HighloadBlock::delete(Beneficiary::TABLE_CODE);
    }
}

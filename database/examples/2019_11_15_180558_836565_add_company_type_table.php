<?php

use App\Models\HL\Company\CompanyType;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Тип компании"
 * Class AddCompanyTables20191115174828648661
 */
class AddCompanyTypeTable20191115180558836565 extends BitrixMigration
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
            ->constructDefault('CompanyType', CompanyType::TABLE_CODE)
            ->setLang('ru', 'Тип компании')
            ->setLang('en', 'Company type')
            ->setLang('cn', 'Company type')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;
        (new UserField())->constructDefault($entityId, 'UF_NAME')
            ->setXmlId('UF_NAME')
            ->setLangDefault('ru', 'Название')
            ->setLangDefault('en', 'Name')
            ->setLangDefault('cn', 'Name')
            ->add();

        CompanyType::create(['UF_NAME' => 'Юридическое лицо']);
        CompanyType::create(['UF_NAME' => 'Индивидуальный предприниматель']);

//        /** @var string $companyEntityId Символьный код сущности "Компания" */
//        $companyEntityId = 'HLBLOCK_' . highloadblock(Company::TABLE_CODE);
//
//        (new UserField())->constructDefault($companyEntityId, 'UF_TAX_NUMBER')
//            ->setXmlId('UF_TAX_NUMBER')
//            ->setLangDefault('ru', 'ИНН')
//            ->setLangDefault('en', 'Tax number')
//            ->setLangDefault('cn', 'Tax number')
//            ->add();
//
//        (new UserField())->constructDefault($companyEntityId, 'UF_OKPO')
//            ->setXmlId('UF_OKPO')
//            ->setLangDefault('ru', 'ОКПО')
//            ->setLangDefault('en', 'OKPO')
//            ->setLangDefault('cn', 'OKPO')
//            ->add();
//
//        (new UserField())->constructDefault($companyEntityId, 'UF_REG_COUNTRY')
//            ->setXmlId('UF_REG_COUNTRY')
//            ->setLangDefault('ru', 'Страна регистрации')
//            ->setLangDefault('en', 'Registration country')
//            ->setLangDefault('cn', 'Registration country')
//            ->add();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        HighloadBlock::delete(CompanyType::TABLE_CODE);
    }
}

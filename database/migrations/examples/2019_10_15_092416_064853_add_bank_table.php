<?php

use App\Models\HL\Bank;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Класс, описывающий миграцию для создания таблицы "Банковские реквизиты"
 * Class AddBankTable20191015092416064853
 */
class AddBankTable20191015092416064853 extends BitrixMigration
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
            ->constructDefault('BankDetails', Bank::TABLE_CODE)
            ->setLang('ru', 'Банковские реквизиты')
            ->setLang('en', 'Bank Details')
            ->setLang('cn', 'Bank Details')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_NAME')
            ->setXmlId('UF_NAME')
            ->setLangDefault('ru', 'Название банка')
            ->setLangDefault('en', 'Bank Name')
            ->setLangDefault('cn', 'Bank Name')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_CHECK_ACCOUNT')
            ->setXmlId('UF_CHECK_ACCOUNT')
            ->setLangDefault('ru', 'Расчетный счет')
            ->setLangDefault('en', 'Check account')
            ->setLangDefault('cn', 'Check account')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_COR_ACCOUNT')
            ->setXmlId('UF_COR_ACCOUNT')
            ->setLangDefault('ru', 'Корр. счет')
            ->setLangDefault('en', 'Cor account')
            ->setLangDefault('cn', 'Cor account')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_BIK')
            ->setXmlId('UF_BIK')
            ->setLangDefault('ru', 'БИК')
            ->setLangDefault('en', 'BIK')
            ->setLangDefault('cn', 'BIK')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_TAX_ID')
            ->setXmlId('UF_TAX_ID')
            ->setLangDefault('ru', 'ИНН')
            ->setLangDefault('en', 'INN')
            ->setLangDefault('cn', 'INN')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_KPP')
            ->setXmlId('UF_KPP')
            ->setLangDefault('ru', 'КПП')
            ->setLangDefault('en', 'KPP')
            ->setLangDefault('cn', 'KPP')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_OKPO')
            ->setXmlId('UF_OKPO')
            ->setLangDefault('ru', 'ОКПО')
            ->setLangDefault('en', 'OKPO')
            ->setLangDefault('cn', 'OKPO')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_CRM_ID')
            ->setXmlId('UF_CRM_ID')
            ->setLangDefault('ru', 'CRM ID')
            ->setLangDefault('en', 'CRM ID')
            ->setLangDefault('cn', 'CRM ID')
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
        $hlBlockId = highloadblock(Bank::TABLE_CODE)['ID'];

        /** @var \Bitrix\Main\ORM\Data\AddResult $result */
        $result = HighloadBlockTable::delete($hlBlockId);

        if (!$result->isSuccess()) {
            $details = implode('; ', $result->getErrorMessages());
            throw new MigrationException("Error deleting highloadblock: " . $details);
        }
    }
}

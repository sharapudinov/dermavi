<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixIblockHelper\HLblock;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddCrmIdToCompanyActivity20190514082849089013 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entity = 'HLBLOCK_' . HLblock::getByTableName('app_company_activity')["ID"];
        (new UserField())->constructDefault($entity, 'CRM_ID')
            ->setXmlId('UF_CRM_ID')
            ->setLangDefault('ru', 'Идентификатор в CRM')
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
        $entity = 'HLBLOCK_' . HLblock::getByTableName('app_company_activity')["ID"];
        $by = '';
        $order = '';
        $field = CUserTypeEntity::GetList(
            [$by => $order],
            ['ENTITY_ID' => $entity, 'FIELD_NAME' => 'UF_CRM_ID']
        )->Fetch();
        UserField::delete($field['ID']);
    }
}

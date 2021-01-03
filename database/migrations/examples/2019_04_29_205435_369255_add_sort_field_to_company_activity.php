<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

class AddSortFieldToCompanyActivity20190429205435369255 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entityId = 'HLBLOCK_' . highloadblock(\App\Models\HL\CompanyActivity::TABLE_CODE)['ID'];

        (new UserField())->constructDefault($entityId, 'SORT')
            ->setXmlId('UF_SORT')
            ->setLangDefault('ru', 'Сортировка')
            ->setLangDefault('en', 'Sort')
            ->setLangDefault('cn', 'Sort')
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
        UserField::delete(
            CUserTypeEntity::GetList([], [
                'ENTITY_ID' => 'HLBLOCK_' . highloadblock(\App\Models\HL\CompanyActivity::TABLE_CODE)['ID'],
                'FIELD_NAME' => 'UF_SORT'
            ])->Fetch()['ID']
        );
    }
}

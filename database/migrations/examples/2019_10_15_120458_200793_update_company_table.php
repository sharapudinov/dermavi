<?php

use App\Models\HL\Company\Company;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для обновления таблицы "Компания"
 * Class UpdateCompanyTable20191015120458200793
 */
class UpdateCompanyTable20191015120458200793 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var array|string[] $whiteListProperties - Массив свойств, которые нельзя удалять */
        $whiteListProperties = ['UF_NAME', 'UF_ACTIVITY_ID', 'UF_EMAIL', 'UF_PHONE'];

        $entityId = 'HLBLOCK_' . highloadblock(Company::TABLE_CODE)['ID'];

        $by = '';
        $order = '';
        $fields = CUserTypeEntity::GetList([$by => $order], ['ENTITY_ID' => $entityId]);

        while ($field = $fields->GetNext()) {
            if (!in_array($field['FIELD_NAME'], $whiteListProperties)) {
                UserField::delete($field['ID']);
            }
        }

        (new UserField())->constructDefault($entityId, 'UF_ADDRESS_ID')
            ->setXmlId('UF_ADDRESS_ID')
            ->setLangDefault('ru', 'Идентификатор адреса')
            ->setLangDefault('en', 'Address id')
            ->setLangDefault('cn', 'Address id')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_BANK_ID')
            ->setXmlId('UF_BANK_ID')
            ->setLangDefault('ru', 'ID банковских реквизитов')
            ->setLangDefault('en', 'Bank details id')
            ->setLangDefault('cn', 'Bank details id')
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
        //
    }
}

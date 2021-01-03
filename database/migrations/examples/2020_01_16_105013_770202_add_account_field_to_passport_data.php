<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\PassportData;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления поля "Основания" в таблицу "Паспортные данные"
 * Class AddAccountFieldToPassportData20200116105013770202
 */
class AddAccountFieldToPassportData20200116105013770202 extends BitrixMigration
{
    /** @var string Символьный код свойства "Основание" */
    private const PROPERTY_CODE = 'ACCOUNT';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entityId = 'HLBLOCK_' . highloadblock(PassportData::TABLE_CODE)['ID'];
        (new UserField())->constructDefault($entityId, self::PROPERTY_CODE)
            ->setXmlId(self::PROPERTY_CODE)
            ->setLangDefault('ru', 'Основание')
            ->setLangDefault('en', 'Account')
            ->setLangDefault('cn', 'Account')
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
        $property = Property::getUserFields(PassportData::TABLE_CODE, [self::PROPERTY_CODE])[0];
        UserField::delete($property['ID']);
    }
}

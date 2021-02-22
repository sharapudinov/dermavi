<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\PassportData;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления поля "Адрес регистрации" в таблицу "Паспортные данные" (Временное поле)
 * Class AddRegisterAddressToPassportData20200116103127874303
 */
class AddRegisterAddressToPassportData20200116103127874303 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'REGISTER_ADDRESS';

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
            ->setLangDefault('ru', 'Адрес регистрации')
            ->setLangDefault('en', 'Register address')
            ->setLangDefault('cn', 'Register address')
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

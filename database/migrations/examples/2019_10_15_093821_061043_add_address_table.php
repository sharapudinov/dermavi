<?php

use App\Models\HL\AddressType;
use App\Models\HL\Address;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

/**
 * Класс, описывающий миграцию для создания таблицы "Адрес"
 * Class AddAddressTable20191015093821061043
 */
class AddAddressTable20191015093821061043 extends BitrixMigration
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
            ->constructDefault('AddressType', AddressType::TABLE_CODE)
            ->setLang('ru', 'Тип адреса')
            ->setLang('en', 'Address Type')
            ->setLang('cn', 'Address Type')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_CRM_ID')
            ->setXmlId('UF_CRM_ID')
            ->setLangDefault('ru', 'CRM ID')
            ->setLangDefault('en', 'CRM ID')
            ->setLangDefault('cn', 'CRM ID')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_NAME')
            ->setXmlId('UF_NAME')
            ->setLangDefault('ru', 'Наименование')
            ->setLangDefault('en', 'Name')
            ->setLangDefault('cn', 'Name')
            ->add();

        $hlBlockId = (new HighloadBlock())
            ->constructDefault('Address', Address::TABLE_CODE)
            ->setLang('ru', 'Адрес')
            ->setLang('en', 'Address')
            ->setLang('cn', 'Address')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_USER_ID')
            ->setXmlId('UF_USER_ID')
            ->setLangDefault('ru', 'Пользователь')
            ->setLangDefault('en', 'User')
            ->setLangDefault('cn', 'User')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_COUNTRY')
            ->setXmlId('UF_COUNTRY')
            ->setLangDefault('ru', 'Страна')
            ->setLangDefault('en', 'Country')
            ->setLangDefault('cn', 'Country')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_REGION')
            ->setXmlId('UF_REGION')
            ->setLangDefault('ru', 'Регион')
            ->setLangDefault('en', 'Region')
            ->setLangDefault('cn', 'Region')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_ZIP')
            ->setXmlId('UF_ZIP')
            ->setLangDefault('ru', 'Индекс')
            ->setLangDefault('en', 'Zip')
            ->setLangDefault('cn', 'Zip')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_CITY')
            ->setXmlId('UF_CITY')
            ->setLangDefault('ru', 'Город')
            ->setLangDefault('en', 'City')
            ->setLangDefault('cn', 'City')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_STREET')
            ->setXmlId('UF_STREET')
            ->setLangDefault('ru', 'Улица')
            ->setLangDefault('en', 'Street')
            ->setLangDefault('cn', 'Street')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_HOUSE')
            ->setXmlId('UF_HOUSE')
            ->setLangDefault('ru', 'Дом')
            ->setLangDefault('en', 'House')
            ->setLangDefault('cn', 'House')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_FLAT')
            ->setXmlId('UF_FLAT')
            ->setLangDefault('ru', 'Квартира/Офис')
            ->setLangDefault('en', 'Flat/office')
            ->setLangDefault('cn', 'Flat/office')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_IS_DEFAULT')
            ->setXmlId('UF_IS_DEFAULT')
            ->setUserType('boolean')
            ->setLangDefault('ru', 'Использовать по-умолчанию')
            ->setLangDefault('en', 'Use as default')
            ->setLangDefault('cn', 'Use as default')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_PHONE')
            ->setXmlId('UF_PHONE')
            ->setLangDefault('ru', 'Телефон')
            ->setLangDefault('en', 'Phone')
            ->setLangDefault('cn', 'Phone')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_EMAIL')
            ->setXmlId('UF_EMAIL')
            ->setLangDefault('ru', 'Email')
            ->setLangDefault('en', 'Email')
            ->setLangDefault('cn', 'Email')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_TYPE_ID')
            ->setXmlId('UF_TYPE_ID')
            ->setLangDefault('ru', 'Тип адреса')
            ->setLangDefault('en', 'Address Type')
            ->setLangDefault('cn', 'Address Type')
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
        $hlBlockId = highloadblock(AddressType::TABLE_CODE)['ID'];

        /** @var \Bitrix\Main\ORM\Data\AddResult $result */
        $result = HighloadBlockTable::delete($hlBlockId);

        if (!$result->isSuccess()) {
            $details = implode('; ', $result->getErrorMessages());
            throw new MigrationException("Error deleting highloadblock: " . $details);
        }

        db()->query('CREATE TABLE IF NOT EXISTS ' . AddressType::TABLE_CODE . ' (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(255) NOT NULL,
            address_type_value VARCHAR(255) NOT NULL
        )');

        $hlBlockId = highloadblock(Address::TABLE_CODE)['ID'];

        /** @var \Bitrix\Main\ORM\Data\AddResult $result */
        $result = HighloadBlockTable::delete($hlBlockId);

        if (!$result->isSuccess()) {
            $details = implode('; ', $result->getErrorMessages());
            throw new MigrationException("Error deleting highloadblock: " . $details);
        }
    }
}

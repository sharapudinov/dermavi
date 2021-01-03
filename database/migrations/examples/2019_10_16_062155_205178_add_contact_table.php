<?php

use App\Models\HL\Contact;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Класс, описывающий миграцию для создания таблицы "Контактное лицо"
 * Class AddContactTable20191016062155205178
 */
class AddContactTable20191016062155205178 extends BitrixMigration
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
            ->constructDefault('Contact', Contact::TABLE_CODE)
            ->setLang('ru', 'Контакт')
            ->setLang('en', 'Contact')
            ->setLang('cn', 'Contact')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_USER_ID')
            ->setXmlId('UF_USER_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Идентификатор пользователя')
            ->setLangDefault('en', 'User id')
            ->setLangDefault('cn', 'User id')
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
        $hlBlockId = highloadblock(Contact::TABLE_CODE)['ID'];

        /** @var \Bitrix\Main\ORM\Data\AddResult $result */
        $result = HighloadBlockTable::delete($hlBlockId);

        if (!$result->isSuccess()) {
            $details = implode('; ', $result->getErrorMessages());
            throw new MigrationException("Error deleting highloadblock: " . $details);
        }
    }
}

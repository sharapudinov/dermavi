<?php

use App\Models\Auxiliary\CRM\Gender;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddGenderCrmTable20190411092259210854 extends BitrixMigration
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
            ->constructDefault('CrmGender', Gender)
            ->setLang('ru', 'Пол пользователя в CRM')
            ->setLang('en', 'CRM Gender')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'XML_ID')
            ->setXmlId('XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Идентификатор')
            ->setLangDefault('en', 'XML ID')
            ->setLangDefault('cn', 'XML ID')
            ->add();

        (new UserField())->constructDefault($entityId, 'VALUE')
            ->setXmlId('VALUE')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Значение')
            ->setLangDefault('en', 'Value')
            ->setLangDefault('cn', 'Value')
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
        $hlBlockId = highloadblock(Gender::TABLE_CODE)['ID'];

        /** @var \Bitrix\Main\ORM\Data\AddResult $result */
        $result = HighloadBlockTable::delete($hlBlockId);

        if (!$result->isSuccess()) {
            $details = implode('; ', $result->getErrorMessages());
            throw new MigrationException("Error deleting highloadblock: " . $details);
        }
    }
}

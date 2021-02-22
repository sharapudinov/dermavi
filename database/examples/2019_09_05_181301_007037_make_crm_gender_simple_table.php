<?php

use App\Models\HL\CrmData\Gender;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Миграция для изменения таблицы "Пол пользователя в CRM" с хайлоадблока на обычную таблицу
 * Class MakeCrmGenderSimpleTable20190905181301007037
 */
class MakeCrmGenderSimpleTable20190905181301007037 extends BitrixMigration
{
    /** @var string $tableCode - Символьный код таблицы */
    private $tableCode = 'app_crm_gender';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        HighloadBlockTable::delete(highloadblock($this->tableCode)['ID']);

        db()->query('CREATE TABLE IF NOT EXISTS ' . $this->tableCode . ' (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(255) NOT NULL,
            gender_value VARCHAR(255) NOT NULL
        )');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        db()->query('DROP TABLE IF EXISTS ' . $this->tableCode);

        $hlBlockId = (new HighloadBlock())
            ->constructDefault('CrmGender', $this->tableCode)
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
}

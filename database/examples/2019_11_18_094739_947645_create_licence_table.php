<?php

use App\Models\HL\Company\Licence;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Лицензия"
 * Class CreateLicenceTable20191118094739947645
 */
class CreateLicenceTable20191118094739947645 extends BitrixMigration
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
            ->constructDefault('Licence', Licence::TABLE_CODE)
            ->setLang('ru', 'Лицензия компании')
            ->setLang('en', 'Company licence')
            ->setLang('cn', 'Company licence')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;
        (new UserField())->constructDefault($entityId, 'UF_TITLE')
            ->setXmlId('UF_TITLE')
            ->setLangDefault('ru', 'Наименование документа')
            ->setLangDefault('en', 'Document title')
            ->setLangDefault('cn', 'Document title')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_ACTIVITY')
            ->setXmlId('UF_ACTIVITY')
            ->setLangDefault('ru', 'Вид деятельности')
            ->setLangDefault('en', 'Activity')
            ->setLangDefault('cn', 'Activity')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_SERIAL_NUMBER')
            ->setXmlId('UF_SERIAL_NUMBER')
            ->setLangDefault('ru', 'Серия и номер')
            ->setLangDefault('en', 'Serial number')
            ->setLangDefault('cn', 'Serial number')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_ISSUE_DATE')
            ->setXmlId('UF_ISSUE_DATE')
            ->setUserType('date')
            ->setLangDefault('ru', 'Дата выдачи')
            ->setLangDefault('en', 'Issue date')
            ->setLangDefault('cn', 'Issue date')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_ISSUE_AUTHORITY')
            ->setXmlId('UF_ISSUE_AUTHORITY')
            ->setLangDefault('ru', 'Орган, выдавший документ')
            ->setLangDefault('en', 'Issue authority')
            ->setLangDefault('cn', 'Issue authority')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_VALID_TO')
            ->setXmlId('UF_VALID_TO')
            ->setUserType('date')
            ->setLangDefault('ru', 'Срок действия')
            ->setLangDefault('en', 'Valid to')
            ->setLangDefault('cn', 'Valid to')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_COMPANY_ID')
            ->setXmlId('UF_COMPANY_ID')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Компания')
            ->setLangDefault('en', 'Company')
            ->setLangDefault('cn', 'Company')
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
        HighloadBlock::delete(Licence::TABLE_CODE);
    }
}

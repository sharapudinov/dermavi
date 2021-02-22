<?php

use App\Models\HL\PassportData;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для обновления таблицы "Паспортные данные"
 * Class UpdatePassportDataTable20191112121655130128
 */
class UpdatePassportDataTable20191112121655130128 extends BitrixMigration
{
    /** @var string $entity Идентификатор хлблока паспортных данных */
    private $entity;

    /**
     * UpdatePassportDataTable20191112121655130128 constructor.
     */
    public function __construct()
    {
        $this->entity = 'HLBLOCK_' . highloadblock(PassportData::TABLE_CODE)['ID'];
        $res = CUserTypeEntity::GetList([], ['ENTITY_ID' => $this->entity]);
        while ($property = $res->GetNext()) {
            UserField::delete($property['ID']);
        }
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        db()->query('CREATE TABLE identity_document_type (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(255) NOT NULL,
            name VARCHAR(255)
        )');

        (new UserField())->constructDefault($this->entity, 'UF_TYPE')
            ->setXmlId('UF_TYPE')
            ->setLangDefault('ru', 'Тип документа')
            ->setLangDefault('en', 'Document type')
            ->setLangDefault('cn', 'Document type')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_SERIES')
            ->setXmlId('UF_SERIES')
            ->setLangDefault('ru', 'Серия')
            ->setLangDefault('en', 'Series')
            ->setLangDefault('cn', 'Series')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_NUMBER')
            ->setXmlId('UF_NUMBER')
            ->setLangDefault('ru', 'Номер')
            ->setLangDefault('en', 'Number')
            ->setLangDefault('cn', 'Number')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_ISSUE_DATE')
            ->setXmlId('UF_ISSUE_DATE')
            ->setUserType('date')
            ->setLangDefault('ru', 'Дата выдачи')
            ->setLangDefault('en', 'Issue date')
            ->setLangDefault('cn', 'Issue date')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_START_DATE')
            ->setXmlId('UF_START_DATE')
            ->setUserType('date')
            ->setLangDefault('ru', 'Дата начала действия')
            ->setLangDefault('en', 'Start date')
            ->setLangDefault('cn', 'Start date')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_VALIDITY_DATE')
            ->setXmlId('UF_VALIDITY_DATE')
            ->setUserType('date')
            ->setLangDefault('ru', 'Дата окончания действия')
            ->setLangDefault('en', 'Validity date')
            ->setLangDefault('cn', 'Validity date')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_DOCUMENT_ORGAN')
            ->setXmlId('UF_DOCUMENT_ORGAN')
            ->setLangDefault('ru', 'Организация, выдавшая документ')
            ->setLangDefault('en', 'Issue organisation')
            ->setLangDefault('cn', 'Issue organisation')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_REG_COUNTRY')
            ->setXmlId('UF_REG_COUNTRY')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Страна регистрации')
            ->setLangDefault('en', 'Registration country')
            ->setLangDefault('cn', 'Registration country')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_REG_CITY')
            ->setXmlId('UF_REG_CITY')
            ->setLangDefault('ru', 'Город регистрации')
            ->setLangDefault('en', 'City country')
            ->setLangDefault('cn', 'City country')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_CITIZENSHIP')
            ->setXmlId('UF_CITIZENSHIP')
            ->setLangDefault('ru', 'Гражданство')
            ->setLangDefault('en', 'Citizenship')
            ->setLangDefault('cn', 'Citizenship')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_MIGRATION_CARD')
            ->setXmlId('UF_MIGRATION_CARD')
            ->setLangDefault('ru', 'Миграционная карта')
            ->setLangDefault('en', 'Migration card')
            ->setLangDefault('cn', 'Migration card')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_CRM_ID')
            ->setXmlId('UF_CRM_ID')
            ->setLangDefault('ru', 'Crm id')
            ->setLangDefault('en', 'Crm id')
            ->setLangDefault('cn', 'Crm id')
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
        db()->query('DROP TABLE IF EXISTS identity_document_type');

        (new UserField())->constructDefault($this->entity, 'UF_SERIAL_NUMBER')
            ->setXmlId('UF_SERIAL_NUMBER')
            ->setLangDefault('ru', 'Серия и номер')
            ->setLangDefault('en', 'Serial number')
            ->setLangDefault('cn', 'Serial number')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_DATE_OF_ISSUE')
            ->setXmlId('UF_DATE_OF_ISSUE')
            ->setUserType('date')
            ->setLangDefault('ru', 'Дата выдачи')
            ->setLangDefault('en', 'Date of issue')
            ->setLangDefault('cn', 'Date of issue')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_AUTHORITY')
            ->setXmlId('UF_AUTHORITY')
            ->setLangDefault('ru', 'Орган, выдавший документ')
            ->setLangDefault('en', 'Issuing authority')
            ->setLangDefault('cn', 'Issuing authority')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_DEP_CODE')
            ->setXmlId('UF_DEP_CODE')
            ->setLangDefault('ru', 'Код подразделения')
            ->setLangDefault('en', 'Department code')
            ->setLangDefault('cn', 'Department code')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_VALID_TO')
            ->setXmlId('UF_VALID_TO')
            ->setUserType('date')
            ->setLangDefault('ru', 'Срок действия')
            ->setLangDefault('en', 'Valid to')
            ->setLangDefault('cn', 'Valid to')
            ->add();
    }
}

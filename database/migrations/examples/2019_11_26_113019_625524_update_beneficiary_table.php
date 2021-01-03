<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\Beneficiary;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для обновления таблицы "Бенефициар"
 * Class UpdateBeneficiaryTable20191126113019625524
 */
class UpdateBeneficiaryTable20191126113019625524 extends BitrixMigration
{
    /** @var string $entity Идентификатор сущности */
    private $entity;

    /**
     * UpdateBeneficiaryTable20191126113019625524 constructor.
     */
    public function __construct()
    {
        $this->entity = 'HLBLOCK_' . highloadblock(Beneficiary::TABLE_CODE)['ID'];
        parent::__construct();
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $properties = Property::getUserFields(
            Beneficiary::TABLE_CODE,
            ['UF_FIELDS']
        );

        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }

        (new UserField())->constructDefault($this->entity, 'UF_NAME')
            ->setXmlId('UF_NAME')
            ->setLangDefault('ru', 'Имя')
            ->setLangDefault('en', 'Name')
            ->setLangDefault('cn', 'Name')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_SURNAME')
            ->setXmlId('UF_SURNAME')
            ->setLangDefault('ru', 'Фамилия')
            ->setLangDefault('en', 'Surname')
            ->setLangDefault('cn', 'Surname')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_MIDDLE_NAME')
            ->setXmlId('UF_MIDDLE_NAME')
            ->setLangDefault('ru', 'Отчество')
            ->setLangDefault('en', 'Middle name')
            ->setLangDefault('cn', 'Middle name')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_PASSPORT_DATA_ID')
            ->setXmlId('UF_PASSPORT_DATA_ID')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Паспортные данные')
            ->setLangDefault('en', 'Passport data')
            ->setLangDefault('cn', 'Passport data')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_COMPANY_ID')
            ->setXmlId('UF_COMPANY_ID')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Компания')
            ->setLangDefault('en', 'Company')
            ->setLangDefault('cn', 'Company')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_IS_OWNER')
            ->setXmlId('UF_IS_OWNER')
            ->setUserType('boolean')
            ->setLangDefault('ru', 'Владелец')
            ->setLangDefault('en', 'Owner')
            ->setLangDefault('cn', 'Owner')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_TAX_NUMBER')
            ->setXmlId('UF_TAX_NUMBER')
            ->setLangDefault('ru', 'ИНН')
            ->setLangDefault('en', 'Tax number')
            ->setLangDefault('cn', 'Tax number')
            ->add();

        (new UserField())->constructDefault($this->entity, 'UF_PUBLIC_OFFICIAL')
            ->setXmlId('UF_PUBLIC_OFFICIAL')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Публичное должностное лицо')
            ->setLangDefault('en', 'Public official')
            ->setLangDefault('cn', 'Public official')
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

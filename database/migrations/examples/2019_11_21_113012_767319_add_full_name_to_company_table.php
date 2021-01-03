<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\Company\Company;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания поля "Полное наименование" в таблице "Компания"
 * Class AddFullNameToCompanyTable20191121113012767319
 */
class AddFullNameToCompanyTable20191121113012767319 extends BitrixMigration
{
    /** @var string $entity Идентификатор сущности */
    private $entity;

    /** @var string $property Символьный код свойства */
    private $property = 'UF_FULL_NAME';

    /**
     * AddDurationOfStayFieldIntoPassportData20191120143832896966 constructor.
     */
    public function __construct()
    {
        $this->entity = 'HLBLOCK_' . highloadblock(Company::TABLE_CODE)['ID'];
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
        (new UserField())->constructDefault($this->entity, $this->property)
            ->setXmlId($this->property)
            ->setSort(99)
            ->setLangDefault('ru', 'Полное наименование')
            ->setLangDefault('en', 'Full name')
            ->setLangDefault('cn', 'Full name')
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
        $properties = Property::getUserFields(
            Company::TABLE_CODE,
            [$this->property]
        );

        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }
    }
}

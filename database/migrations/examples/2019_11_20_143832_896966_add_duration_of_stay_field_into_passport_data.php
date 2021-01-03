<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\PassportData;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания поля "Срок пребывания в РФ" в таблице "Паспортные данные"
 * Class AddDurationOfStayFieldIntoPassportData20191120143832896966
 */
class AddDurationOfStayFieldIntoPassportData20191120143832896966 extends BitrixMigration
{
    /** @var string $entity Идентификатор сущности */
    private $entity;

    /** @var string $property Символьный код свойства */
    private $property = 'UF_STAY_DURATION';

    /**
     * AddDurationOfStayFieldIntoPassportData20191120143832896966 constructor.
     */
    public function __construct()
    {
        $this->entity = 'HLBLOCK_' . highloadblock(PassportData::TABLE_CODE)['ID'];
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
            ->setLangDefault('ru', 'Сроки пребывания в РФ')
            ->setLangDefault('en', 'Duration of stay in RF')
            ->setLangDefault('cn', 'Duration of stay in RF')
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
            PassportData::TABLE_CODE,
            [$this->property]
        );

        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }
    }
}

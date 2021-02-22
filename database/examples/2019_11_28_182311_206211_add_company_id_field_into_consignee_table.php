<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\Consignee;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления полей в таблицу "Грузополучатель"
 * Class AddCompanyIdFieldIntoConsigneeTable20191128182311206211
 */
class AddCompanyIdFieldIntoConsigneeTable20191128182311206211 extends BitrixMigration
{
    /** @var array|array[] $properties Массив, описывающий свойства */
    private $properties = [
        [
            'name' => 'UF_COMPANY_ID',
            'ru' => 'Компания',
            'en' => 'Company'
        ]
    ];

    /** @var string $entity Идентификатор сущности */
    private $entity;

    /**
     * AddCompanyIdFieldIntoConsigneeTable20191128182311206211 constructor.
     */
    public function __construct()
    {
        $this->entity = 'HLBLOCK_' . highloadblock(Consignee::TABLE_CODE)['ID'];
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
        foreach ($this->properties as $property) {
            (new UserField())->constructDefault($this->entity, $property['name'])
                ->setXmlId($property['name'])
                ->setLangDefault('ru', $property['ru'])
                ->setLangDefault('en', $property['en'])
                ->setLangDefault('cn', $property['en'])
                ->add();
        }
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
            Consignee::TABLE_CODE,
            array_column($this->properties, 'name')
        );

        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }
    }
}

<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\PassportData;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления поля "Сканы паспорта" в таблице "Паспортные данные"
 * Class UpdatePassportDataTable20191118153334511699
 */
class UpdatePassportDataTable20191118153334511699 extends BitrixMigration
{
    /** @var string $entityId Идентификатор сущности */
    private $entityId;

    /** @var array|string[] $property Массив, описывающий свойство */
    private $property;

    /**
     * UpdatePassportDataTable20191118153334511699 constructor.
     */
    public function __construct()
    {
        $this->entityId = 'HLBLOCK_' . highloadblock(PassportData::TABLE_CODE)['ID'];
        $this->property = [
            'name' => 'UF_SCANS',
            'ru' => 'Сканы паспорта',
            'en' => 'Passport scans'
        ];

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
        (new UserField())->constructDefault($this->entityId, $this->property['name'])
            ->setXmlId($this->property['name'])
            ->setUserType('file')
            ->setMultiple(true)
            ->setLangDefault('ru', $this->property['ru'])
            ->setLangDefault('en', $this->property['en'])
            ->setLangDefault('cn', $this->property['en'])
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
        UserField::delete(
            Property::getUserFields(PassportData::TABLE_CODE, [$this->property['name']])[0]['ID']
        );
    }
}

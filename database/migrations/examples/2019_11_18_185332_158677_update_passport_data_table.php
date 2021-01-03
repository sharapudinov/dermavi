<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\PassportData;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления полей в таблицу "Паспортные данные"
 * Class UpdatePassportDataTable20191118185332158677
 */
class UpdatePassportDataTable20191118185332158677 extends BitrixMigration
{
    /** @var array|array[] $fields Массив, описывающий свойства для добавления */
    private $fields;

    /**
     * UpdatePassportDataTable20191118185332158677 constructor.
     */
    public function __construct()
    {
        $this->fields = [
            [
                'name' => 'UF_ISSUE_ORG_CODE',
                'type' => 'string',
                'ru' => 'Код подразделения',
                'en' => 'Issue organization code'
            ],
            [
                'name' => 'UF_BIRTH_COUNTRY',
                'type' => 'integer',
                'ru' => 'Страна рождения',
                'en' => 'Birth country'
            ],
            [
                'name' => 'UF_BIRTH_PLACE',
                'type' => 'string',
                'ru' => 'Место рождения',
                'en' => 'Birth place'
            ],
            [
                'name' => 'UF_BIRTHDAY',
                'type' => 'date',
                'ru' => 'Дата рождения',
                'en' => 'Birthday'
            ]
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
        $entityId = 'HLBLOCK_' . highloadblock(PassportData::TABLE_CODE)['ID'];

        foreach ($this->fields as $field) {
            (new UserField())->constructDefault($entityId, $field['name'])
                ->setXmlId($field['name'])
                ->setUserType($field['type'])
                ->setLangDefault('ru', $field['ru'])
                ->setLangDefault('en', $field['en'])
                ->setLangDefault('cn', $field['en'])
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
            PassportData::TABLE_CODE,
            array_column($this->fields, 'name')
        );

        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }
    }
}

<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\PersonalForm;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления timestamp полей в таблицу "Личная анкета"
 * Class AddPersonalFormTimestampsFields20200207164815419908
 */
class AddPersonalFormTimestampsFields20200207164815419908 extends BitrixMigration
{
    /** @var array|array[] $properties Массив, описывающий свойства для добавления */
    private $properties = [
        'UF_DATE_CREATE' => [
            'ru' => 'Дата создания',
            'en' => 'Date create'
        ],
        'UF_DATE_UPDATE' => [
            'ru' => 'Дата обновления',
            'en' => 'Date update'
        ]
    ];

    /** @var string $entityId Символьный код сущности */
    private $entityId;

    /**
     * AddPersonalFormTimestampsFields20200207164815419908 constructor.
     */
    public function __construct()
    {
        $this->entityId = 'HLBLOCK_' . highloadblock(PersonalForm::TABLE_CODE)['ID'];
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        foreach ($this->properties as $propertyCode => $fields) {
            (new UserField())->constructDefault($this->entityId, $propertyCode)
                ->setXmlId($propertyCode)
                ->setUserType('date')
                ->setLangDefault('ru', $fields['ru'])
                ->setLangDefault('en', $fields['en'])
                ->setLangDefault('cn', $fields['en'])
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
            PersonalForm::TABLE_CODE,
            array_keys($this->properties)
        );

        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }
    }
}

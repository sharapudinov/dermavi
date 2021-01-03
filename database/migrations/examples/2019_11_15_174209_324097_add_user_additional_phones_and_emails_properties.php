<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания свойств "Дополнительные телефоны" и "Дополнительные email'ы"
 * Class AddPersonalFormTables20191115173211569635
 */
class AddUserAdditionalPhonesAndEmailsProperties20191115174209324097 extends BitrixMigration
{
    /** @var array|array $properties Массив, описывающий свойства */
    private static $properties = [
        'UF_ADD_PHONES' => [
            'ru' => 'Дополнительные телефоны',
            'en' => 'Additional phones'
        ],
        'UF_ADD_EMAILS' => [
            'ru' => 'Дополнительные email',
            'en' => 'Additional emails'
        ]
    ];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        foreach (self::$properties as $property => $propertyInfo) {
            (new UserField())->constructDefault('USER', $property)
                ->setXmlId($property)
                ->setMultiple(true)
                ->setLangDefault('ru', $propertyInfo['ru'])
                ->setLangDefault('en', $propertyInfo['en'])
                ->setLangDefault('cn', $propertyInfo['en'])
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
        foreach (self::$properties as $property => $propertyInfo) {
            UserField::delete(
                CUserTypeEntity::GetList([], ['ENTITY_ID' => 'USER', 'FIELD_NAME' => $property])->Fetch()['ID']
            );
        }
    }
}

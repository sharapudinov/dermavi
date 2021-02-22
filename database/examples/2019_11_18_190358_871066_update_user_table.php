<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для удаления свойств из таблицы пользователей
 * Class UpdateUserTable20191118190358871066
 */
class UpdateUserTable20191118190358871066 extends BitrixMigration
{
    /** @var array|array[] $fields Массив, описывающий свойства */
    private $fields = [
        [
            'name' => 'UF_CITIZENSHIP',
            'ru' => 'Гражданство',
            'en' => 'Citizenship'
        ],
        [
            'name' => 'UF_BIRTH_COUNTRY',
            'ru' => 'Birth country',
            'en' => 'Birth country'
        ],
        [
            'name' => 'UF_PLACE_OF_BIRTH',
            'ru' => 'Место рождения',
            'en' => 'Place of birth'
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
        foreach ($this->fields as $field) {
            UserField::delete(
                CUserTypeEntity::GetList([], ['ENTITY_ID' => 'USER', 'FIELD_NAME' => $field['name']])->Fetch()['ID']
            );
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
        foreach ($this->fields as $field) {
            (new UserField())->constructDefault('USER', $field['name'])
                ->setXmlId($field['name'])
                ->setLangDefault('ru', $field['ru'])
                ->setLangDefault('en', $field['en'])
                ->setLangDefault('cn', $field['en'])
                ->add();
        }
    }
}

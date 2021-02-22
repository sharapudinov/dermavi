    <?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

class AddAuthFieldsToUser20190416173528690656 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $fields = [
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_APPEAL',
                'XML_ID' => 'UF_APPEAL',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Обращение (mr, ms, miss)',
                    'en' => 'Обращение (mr, ms, miss)',
                    'cn' => 'Обращение (mr, ms, miss)'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_COMPANY_ID',
                'XML_ID' => 'UF_COMPANY_ID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Компания',
                    'en' => 'Company',
                    'cn' => 'Company'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_HASH',
                'XML_ID' => 'UF_HASH',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Хэш пользователя',
                    'en' => 'User hash',
                    'cn' => 'User hash'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_CRM_ID',
                'XML_ID' => 'UF_CRM_ID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'CRM id пользователя',
                    'en' => 'User CRM id',
                    'cn' => 'User CRM id'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_USER_NAME_RU',
                'XML_ID' => 'UF_USER_NAME_RU',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Имя (рус)',
                    'en' => 'Name (ru)',
                    'cn' => 'Name (ru)'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_USER_NAME_EN',
                'XML_ID' => 'UF_USER_NAME_EN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Имя (англ)',
                    'en' => 'Name (en)',
                    'cn' => 'Name (en)'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_USER_NAME_CN',
                'XML_ID' => 'UF_USER_NAME_CN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Имя (кит)',
                    'en' => 'Name (cn)',
                    'cn' => 'Name (cn)'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_USER_SURNAME_RU',
                'XML_ID' => 'UF_USER_SURNAME_RU',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Фамилия (рус)',
                    'en' => 'Surname (ru)',
                    'cn' => 'Surname (ru)'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_USER_SURNAME_EN',
                'XML_ID' => 'UF_USER_SURNAME_EN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Фамилия (англ)',
                    'en' => 'Surname (en)',
                    'cn' => 'Surname (en)'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_USER_SURNAME_CN',
                'XML_ID' => 'UF_USER_SURNAME_CN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Фамилия (кит)',
                    'en' => 'Surname (cn)',
                    'cn' => 'Surname (cn)'
                ]
            ]
        ];

        foreach ($fields as $field) {
            $this->addUF($field);
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
        $fieldsNames = [
            'UF_APPEAL',
            'UF_COMPANY_ID',
            'UF_HASH',
            'UF_CRM_ID',
            'UF_USER_NAME_RU',
            'UF_USER_NAME_EN',
            'UF_USER_NAME_CN',
            'UF_USER_SURNAME_RU',
            'UF_USER_SURNAME_EN',
            'UF_USER_SURNAME_CN',
        ];
        foreach ($fieldsNames as $fieldName) {
            $by = '';
            $order = '';
            $field = CUserTypeEntity::GetList(
                [$by => $order],
                ['ENTITY_ID' => 'USER', 'FIELD_NAME' => $fieldName]
            )->Fetch();

            UserField::delete($field['ID']);
        }
    }
}

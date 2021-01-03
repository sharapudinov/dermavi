<?php

use App\Models\HL\PassportData;
use App\Models\HL\RegistrationAddress;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddB2cUserFieldsForDocuments20190625150533664803 extends BitrixMigration
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
            'NAME' => 'PassportData',
            'TABLE_NAME' => PassportData::TABLE_CODE,
        ];
        $result = HighloadBlockTable::add($fields);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении hl-блока: ' . implode(', ', $errors));
        }

        $highloadBlockId = $result->getId();
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        //Добавляем языковые названия для HL-блока
        $result = HighloadBlockLangTable::add([
            "ID" => $highloadBlockId,
            "LID" => "ru",
            "NAME" => 'Паспортные данные',
        ]);

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении языкового названия для hl-блока ' . PassportData::TABLE_CODE . ': ' . implode(', ', $errors));
        }

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_SERIAL_NUMBER',
                'XML_ID' => 'UF_SERIAL_NUMBER',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Серия и номер',
                    'en' => 'Serial number',
                    'cn' => 'Serial number'
                ]
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_DATE_OF_ISSUE',
                'XML_ID' => 'UF_DATE_OF_ISSUE',
                'USER_TYPE_ID' => 'date',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Дата выдачи',
                    'en' => 'Date of issue',
                    'cn' => 'Date of issue'
                ]
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_AUTHORITY',
                'XML_ID' => 'UF_AUTHORITY',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Орган, выдавший документ',
                    'en' => 'Issuing authority',
                    'cn' => 'Issuing authority'
                ]
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_DEP_CODE',
                'XML_ID' => 'UF_DEP_CODE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Код подразделения',
                    'en' => 'Department code',
                    'cn' => 'Department code'
                ]
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_VALID_TO',
                'XML_ID' => 'UF_VALID_TO',
                'USER_TYPE_ID' => 'date',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Срок действия',
                    'en' => 'Valid to',
                    'cn' => 'Valid to'
                ]
            ]
        ];

        foreach ($fields as $field) {
            $this->addUF($field);
        }

        $fields = [
            'NAME' => 'RegistrationAddres',
            'TABLE_NAME' => RegistrationAddress::TABLE_CODE,
        ];
        $result = HighloadBlockTable::add($fields);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении hl-блока: ' . implode(', ', $errors));
        }

        $highloadBlockId = $result->getId();
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        //Добавляем языковые названия для HL-блока
        $result = HighloadBlockLangTable::add([
            "ID" => $highloadBlockId,
            "LID" => "ru",
            "NAME" => 'Адрес регистрации',
        ]);

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении языкового названия для hl-блока ' . RegistrationAddress::TABLE_CODE . ': ' . implode(', ', $errors));
        }

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_ZIP_CODE',
                'XML_ID' => 'UF_ZIP_CODE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Индекс',
                    'en' => 'Zip code',
                    'cn' => 'Zip code'
                ]
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_COUNTRY_ID',
                'XML_ID' => 'UF_COUNTRY_ID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Страна',
                    'en' => 'Country',
                    'cn' => 'Country'
                ]
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_ADDRESS',
                'XML_ID' => 'UF_ADDRESS',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Адрес',
                    'en' => 'Address',
                    'cn' => 'Address'
                ]
            ]
        ];

        foreach ($fields as $field) {
            $this->addUF($field);
        }

        $fields = [
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_TAX_NUMBER',
                'XML_ID' => 'UF_TAX_NUMBER',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'ИНН',
                    'en' => 'Tax number',
                    'cn' => 'Tax number'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_CITIZENSHIP',
                'XML_ID' => 'UF_CITIZENSHIP',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Гражданство',
                    'en' => 'Citizenship',
                    'cn' => 'Citizenship'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_BIRTH_COUNTRY',
                'XML_ID' => 'UF_BIRTH_COUNTRY',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Страна рождения',
                    'en' => 'Birth country',
                    'cn' => 'Birth country'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_PLACE_OF_BIRTH',
                'XML_ID' => 'UF_PLACE_OF_BIRTH',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Место рождения',
                    'en' => 'Place of birth',
                    'cn' => 'Place of birth'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_PASSPORT_ID',
                'XML_ID' => 'UF_PASSPORT_ID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Идентификатор паспортных данных',
                    'en' => 'Passport data id',
                    'cn' => 'Passport data id'
                ]
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_REGISTRATION_ID',
                'XML_ID' => 'UF_REGISTRATION_ID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Идентификатор адреса регистрации',
                    'en' => 'Registration id',
                    'cn' => 'Registration id'
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
        $highloadBlockId = HLblock::getByTableName(PassportData::TABLE_CODE)["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }

        $highloadBlockId = HLblock::getByTableName(RegistrationAddress::TABLE_CODE)["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }

        $by = '';
        $order = '';
        $fields[] = CUserTypeEntity::GetList(
            [$by => $order],
            ['ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_TAX_NUMBER']
        )->Fetch();
        $fields[] = CUserTypeEntity::GetList(
            [$by => $order],
            ['ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_CITIZENSHIP']
        )->Fetch();
        $fields[] = CUserTypeEntity::GetList(
            [$by => $order],
            ['ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_BIRTH_COUNTRY']
        )->Fetch();
        $fields[] = CUserTypeEntity::GetList(
            [$by => $order],
            ['ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_PLACE_OF_BIRTH']
        )->Fetch();
        $fields[] = CUserTypeEntity::GetList(
            [$by => $order],
            ['ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_PASSPORT_ID']
        )->Fetch();
        $fields[] = CUserTypeEntity::GetList(
            [$by => $order],
            ['ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_REGISTRATION_ID']
        )->Fetch();
        foreach ($fields as $field) {
            UserField::delete($field['ID']);
        }
    }
}

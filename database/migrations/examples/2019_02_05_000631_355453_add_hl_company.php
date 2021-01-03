<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddHlCompany20190205000631355453 extends BitrixMigration
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
            'NAME' => 'Company',
            'TABLE_NAME' => 'app_company',
        ];
        $result = HighloadBlockTable::add($fields);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении hl-блока: ' . implode(', ', $errors));
        }

        $highloadBlockId = $result->getId();
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        //Добавляем языковые названия для HL-блока
        if (!empty($dict['NAME_RU'])) {
            $result = HighloadBlockLangTable::add([
                "ID" => $highloadBlockId,
                "LID" => "ru",
                "NAME" => 'Компании',
            ]);
        }

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors));
        }

        $hlCompanyActivity = HLblock::getByTableName('app_company_activity')["ID"];
        $showField = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => $hlCompanyActivity,
                'FIELD_NAME' => 'UF_NAME_RU',
            ]
        )->Fetch();

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_NAME',
                'XML_ID' => 'UF_NAME',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Название',
                        'en' => 'Name',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Название',
                        'en' => 'Name',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Название',
                        'en' => 'Name',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Название',
                        'en' => 'Name',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Название',
                        'en' => 'Name',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_ACTIVITY_ID',
                'XML_ID' => 'UF_ACTIVITY_ID',
                'USER_TYPE_ID' => 'hlblock',
                'MANDATORY' => 'N',
                'SETTINGS' => [
                    'HLBLOCK_ID' => $hlCompanyActivity,
                    'HLFIELD_ID' => $showField['ID'],
                ],
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Сфера деятельности',
                        'en' => 'Activity',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Сфера деятельности',
                        'en' => 'Activity',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Сфера деятельности',
                        'en' => 'Activity',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Сфера деятельности',
                        'en' => 'Activity',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Сфера деятельности',
                        'en' => 'Activity',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_TIN',
                'XML_ID' => 'UF_TIN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'ИНН',
                        'en' => 'TIN',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'ИНН',
                        'en' => 'TIN',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'ИНН',
                        'en' => 'TIN',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'ИНН',
                        'en' => 'TIN',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'ИНН',
                        'en' => 'TIN',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_EMAIL',
                'XML_ID' => 'UF_EMAIL',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Email',
                        'en' => 'Email',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Email',
                        'en' => 'Email',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Email',
                        'en' => 'Email',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Email',
                        'en' => 'Email',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Email',
                        'en' => 'Email',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_PHONE',
                'XML_ID' => 'UF_PHONE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Телефон',
                        'en' => 'Phone',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Телефон',
                        'en' => 'Phone',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Телефон',
                        'en' => 'Phone',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Телефон',
                        'en' => 'Phone',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Телефон',
                        'en' => 'Phone',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_COUNTRY',
                'XML_ID' => 'UF_COUNTRY',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Страна',
                        'en' => 'Country',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Страна',
                        'en' => 'Country',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Страна',
                        'en' => 'Country',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Страна',
                        'en' => 'Country',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Страна',
                        'en' => 'Country',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_INDEX',
                'XML_ID' => 'UF_INDEX',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Почтовый индекс',
                        'en' => 'Index',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Почтовый индекс',
                        'en' => 'Index',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Почтовый индекс',
                        'en' => 'Index',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Почтовый индекс',
                        'en' => 'Index',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Почтовый индекс',
                        'en' => 'Index',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_REGION',
                'XML_ID' => 'UF_REGION',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Регион',
                        'en' => 'Region',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Регион',
                        'en' => 'Region',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Регион',
                        'en' => 'Region',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Регион',
                        'en' => 'Region',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Регион',
                        'en' => 'Region',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_CITY',
                'XML_ID' => 'UF_CITY',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Город',
                        'en' => 'City',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Город',
                        'en' => 'City',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Город',
                        'en' => 'City',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Город',
                        'en' => 'City',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Город',
                        'en' => 'City',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_STREET',
                'XML_ID' => 'UF_STREET',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Улица',
                        'en' => 'Street',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Улица',
                        'en' => 'Street',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Улица',
                        'en' => 'Street',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Улица',
                        'en' => 'Street',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Улица',
                        'en' => 'Street',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_HOUSE',
                'XML_ID' => 'UF_HOUSE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Дом',
                        'en' => 'House',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Дом',
                        'en' => 'House',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Дом',
                        'en' => 'House',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Дом',
                        'en' => 'House',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Дом',
                        'en' => 'House',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_FLAT',
                'XML_ID' => 'UF_FLAT',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Квартира',
                        'en' => 'Flat',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Квартира',
                        'en' => 'Flat',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Квартира',
                        'en' => 'Flat',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Квартира',
                        'en' => 'Flat',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Квартира',
                        'en' => 'Flat',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_BANK',
                'XML_ID' => 'UF_BANK',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Банк',
                        'en' => 'Bank',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Банк',
                        'en' => 'Bank',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Банк',
                        'en' => 'Bank',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Банк',
                        'en' => 'Bank',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Банк',
                        'en' => 'Bank',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_BIK',
                'XML_ID' => 'UF_BIK',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'БИК',
                        'en' => 'BIK',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'БИК',
                        'en' => 'BIK',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'БИК',
                        'en' => 'BIK',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'БИК',
                        'en' => 'BIK',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'БИК',
                        'en' => 'BIK',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_COR_ACCOUNT',
                'XML_ID' => 'UF_COR_ACCOUNT',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Корр. счет',
                        'en' => 'Cor account',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Корр. счет',
                        'en' => 'Cor account',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Корр. счет',
                        'en' => 'Cor account',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Корр. счет',
                        'en' => 'Cor account',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Корр. счет',
                        'en' => 'Cor account',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_CHECK_ACCOUNT',
                'XML_ID' => 'UF_CHECK_ACCOUNT',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Расчетный счет',
                        'en' => 'Checking account',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Расчетный счет',
                        'en' => 'Checking account',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Расчетный счет',
                        'en' => 'Checking account',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Расчетный счет',
                        'en' => 'Checking account',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Расчетный счет',
                        'en' => 'Checking account',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_KPP',
                'XML_ID' => 'UF_KPP',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'КПП',
                        'en' => 'KPP',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'КПП',
                        'en' => 'KPP',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'КПП',
                        'en' => 'KPP',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'КПП',
                        'en' => 'KPP',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'КПП',
                        'en' => 'KPP',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_OKPO',
                'XML_ID' => 'UF_OKPO',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'ОКПО',
                        'en' => 'OKPO',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'ОКПО',
                        'en' => 'OKPO',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'ОКПО',
                        'en' => 'OKPO',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'ОКПО',
                        'en' => 'OKPO',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'ОКПО',
                        'en' => 'OKPO',
                    ],
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
        foreach (static::getListDicts() as $dict) {
            $highloadBlockId = HLblock::getByTableName('app_company')["ID"];
            $result = HighloadBlockTable::delete($highloadBlockId);
            if (!$result->isSuccess()) {
                $errors = $result->getErrorMessages();
                throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
            }
        }
    }
}

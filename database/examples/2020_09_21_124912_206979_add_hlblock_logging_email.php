<?php

use App\Enum\HlBlockEnum;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddHlblockLoggingEmail20200921124912206979 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $fields = [
            'NAME'       => HlBlockEnum::LOGGING_EMAIL,
            'TABLE_NAME' => 'adv_logging_email',
        ];

        $result = HighloadBlockTable::add($fields);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении hl-блока: ' . implode(', ', $errors));
        }

        $highloadBlockId       = $result->getId();
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        //Добавляем языковые названия для HL-блока
        $result = HighloadBlockLangTable::add(
            [
                "ID"   => $highloadBlockId,
                "LID"  => "ru",
                "NAME" => 'Логи отправки e-mail',
            ]
        );

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException(
                'Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors)
            );
        }

        $fields = [
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_RECIPIENT',
                'XML_ID'            => 'UF_RECIPIENT',
                'USER_TYPE_ID'      => 'enumeration',
                'SHOW_FILTER'       => 'S',
                'MANDATORY'         => 'N',
                'SORT'              => 200,
                'SETTINGS'          =>
                    [
                        'DEFAULT_VALUE' => 1,
                        'DISPLAY'       => 'list',
                        'LIST_HEIGHT'   => 1,
                    ],
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Адресат',
                        'en' => 'Recipient',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Адресат',
                        'en' => 'Recipient',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Адресат',
                        'en' => 'Recipient',
                    ],
                'ENUM_VALUES'       =>
                    [
                        1 =>
                            [
                                'VALUE'  => 'Клиент',
                                'DEF'    => 'Y',
                                'SORT'   => '100',
                                'XML_ID' => 'client',
                            ],
                        2 =>
                            [
                                'VALUE'  => 'Менеджер',
                                'DEF'    => 'N',
                                'SORT'   => '200',
                                'XML_ID' => 'manager',
                            ],
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_EMAIL',
                'XML_ID'            => 'UF_EMAIL',
                'USER_TYPE_ID'      => 'string',
                'SHOW_FILTER'       => 'S',
                'MANDATORY'         => 'N',
                'SORT'              => 300,
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'E - mail',
                        'en' => 'Recipient',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'E - mail',
                        'en' => 'Recipient',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'E - mail',
                        'en' => 'Recipient',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_DATE_SEND',
                'XML_ID'            => 'UF_DATE_SEND',
                'USER_TYPE_ID'      => 'datetime',
                'SHOW_FILTER'       => 'S',
                'MANDATORY'         => 'N',
                'SORT'              => 400,
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Дата отправки',
                        'en' => 'Date send',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Дата отправки',
                        'en' => 'Date send',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Дата отправки',
                        'en' => 'Date send',
                    ],
            ],

            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_EVENT_ID',
                'XML_ID'            => 'UF_EVENT_ID',
                'USER_TYPE_ID'      => 'string',
                'SHOW_FILTER'       => 'S',
                'MANDATORY'         => 'N',
                'SORT'              => 500,
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Id события',
                        'en' => 'Id event',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Id события',
                        'en' => 'Id event',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Id события',
                        'en' => 'Id event',
                    ],
            ],

            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_EVENT_NAME',
                'XML_ID'            => 'UF_EVENT_NAME',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'N',
                'SORT'              => 600,
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Название события',
                        'en' => 'Event name',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Название события',
                        'en' => 'Event name',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Название события',
                        'en' => 'Event name',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_BODY',
                'XML_ID'            => 'UF_BODY',
                'USER_TYPE_ID'      => 'html_show_email',
                'MANDATORY'         => 'N',
                'SHOW_IN_LIST'      => 'N',
                'EDIT_IN_LIST'      => 'N',
                'SORT'              => 700,
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Тело письма',
                        'en' => 'Body',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Тело письма',
                        'en' => 'Body',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Тело письма',
                        'en' => 'Body',
                    ],
            ],
        ];
        foreach ($fields as $field) {
            $enum = [];
            if (!empty($field['ENUM_VALUES'])) {
                $enum = $field['ENUM_VALUES'];
                unset($field['ENUM_VALUES']);
            }
            $id = $this->addUF($field);

            if (!empty($enum)) {
                $arAddEnum = [];
                foreach ($enum as $i => $val) {
                    $arAddEnum['n' . $i] = $val;
                }
                $obEnum = new CUserFieldEnum();
                $obEnum->SetEnumValues($id, $arAddEnum);
            }
        }
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {
        //
    }
}

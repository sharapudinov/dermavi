<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddHlCallbackform20201029163459434791 extends BitrixMigration
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
            'NAME' => 'FormCallBack',
            'TABLE_NAME' => 'app_form_callback_header',
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
                "NAME" => 'Форма обратного звонка',
            ]);
        }

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors));
        }

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_USER_NAME',
                'XML_ID' => 'UF_USER_NAME',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_USER_SURNAME',
                'XML_ID' => 'UF_USER_SURNAME',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_USER_EMAIL',
                'XML_ID' => 'UF_USER_EMAIL',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_USER_THEME',
                'XML_ID' => 'UF_USER_THEME',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_USER_QUESTION',
                'XML_ID' => 'UF_USER_QUESTION',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
            ],
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
        //
    }
}

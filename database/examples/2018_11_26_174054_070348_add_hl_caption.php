<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Application;

class AddHlCaption20181126174054070348 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $fields = [
            'NAME' => 'TracingCaption',
            'TABLE_NAME' => 'tracing_caption',
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
            "NAME" => 'Титры для трейсинга',
        ]);

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors));
        }

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_TEXT_EN',
                'XML_ID' => 'UF_TEXT_EN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Текст титров (англ)',
                    'en' => 'Caption text (eng)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Текст титров (англ)',
                    'en' => 'Caption text (eng)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Текст титров (англ)',
                    'en' => 'Caption text (eng)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Текст титров (англ)',
                    'en' => 'Caption text (eng)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Текст титров (англ)',
                    'en' => 'Caption text (eng)',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_TEXT_RU',
                'XML_ID' => 'UF_TEXT_RU',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Текст титров (рус)',
                    'en' => 'Caption text (rus)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Текст титров (рус)',
                    'en' => 'Caption text (rus)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Текст титров (рус)',
                    'en' => 'Caption text (rus)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Текст титров (рус)',
                    'en' => 'Caption text (rus)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Текст титров (рус)',
                    'en' => 'Caption text (rus)',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_TEXT_CN',
                'XML_ID' => 'UF_TEXT_CN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Текст титров (кит)',
                    'en' => 'Caption text (cn)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Текст титров (кит)',
                    'en' => 'Caption text (cn)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Текст титров (кит)',
                    'en' => 'Caption text (cn)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Текст титров (кит)',
                    'en' => 'Caption text (cn)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Текст титров (кит)',
                    'en' => 'Caption text (cn)',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_NAME',
                'XML_ID' => 'UF_NAME',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Название параметра',
                    'en' => 'Name param',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Название параметра',
                    'en' => 'Name param',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Название параметра',
                    'en' => 'Name param',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Название параметра',
                    'en' => 'Name param',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Название параметра',
                    'en' => 'Name param',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_INDEX',
                'XML_ID' => 'UF_INDEX',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Сортировка',
                    'en' => 'Sort',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Сортировка',
                    'en' => 'Sort',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Сортировка',
                    'en' => 'Sort',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Сортировка',
                    'en' => 'Sort',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Сортировка',
                    'en' => 'Sort',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_ACTIVE',
                'XML_ID' => 'UF_ACTIVE',
                'USER_TYPE_ID' => 'boolean',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Активность',
                    'en' => 'Active',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Активность',
                    'en' => 'Active',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Активность',
                    'en' => 'Active',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Активность',
                    'en' => 'Active',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Активность',
                    'en' => 'Active',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_SCENARIO_NUMBER',
                'XML_ID' => 'UF_SCENARIO_NUMBER',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Номер сценария',
                    'en' => 'Scenario number',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Номер сценария',
                    'en' => 'Scenario number',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Номер сценария',
                    'en' => 'Scenario number',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Номер сценария',
                    'en' => 'Scenario number',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Номер сценария',
                    'en' => 'Scenario number',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_START_TIME',
                'XML_ID' => 'UF_START_TIME',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Время начала показа',
                    'en' => 'Time start',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Время начала показа',
                    'en' => 'Time start',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Время начала показа',
                    'en' => 'Time start',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Время начала показа',
                    'en' => 'Time start',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Время начала показа',
                    'en' => 'Time start',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_END_TIME',
                'XML_ID' => 'UF_END_TIME',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Время завершения показа',
                    'en' => 'Time end',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Время завершения показа',
                    'en' => 'Time end',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Время завершения показа',
                    'en' => 'Time end',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Время завершения показа',
                    'en' => 'Time end',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Время завершения показа',
                    'en' => 'Time end',
                ],
            ],
        ];

        foreach ($fields as $field) {
            $this->addUF($field);
        }

        foreach (['EN', 'RU', 'CN'] as $lang) {
            Application::getConnection()->query("ALTER TABLE `tracing_caption` MODIFY COLUMN `UF_TEXT_{$lang}` TEXT");
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     */
    public function down()
    {
        $highloadBlockId = HLblock::getByTableName('tracing_caption')["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}

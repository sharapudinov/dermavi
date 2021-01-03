<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddHlChapter20181126174035742122 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $fields = [
            'NAME' => 'TracingChapter',
            'TABLE_NAME' => 'tracing_chapter',
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
            "NAME" => 'Части трейсинга',
        ]);

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors));
        }

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_CODE',
                'XML_ID' => 'UF_CODE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Символьный код',
                    'en' => 'Code',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Символьный код',
                    'en' => 'Code',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Символьный код',
                    'en' => 'Code',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Символьный код',
                    'en' => 'Code',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Символьный код',
                    'en' => 'Code',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_NAME_EN',
                'XML_ID' => 'UF_NAME_EN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Название (англ)',
                    'en' => 'Name (eng)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Название (англ)',
                    'en' => 'Name (eng)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Название (англ)',
                    'en' => 'Name (eng)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Название (англ)',
                    'en' => 'Name (eng)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Название (англ)',
                    'en' => 'Name (eng)',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_NAME_RU',
                'XML_ID' => 'UF_NAME_RU',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Название (рус)',
                    'en' => 'Name (rus)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Название (рус)',
                    'en' => 'Name (rus)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Название (рус)',
                    'en' => 'Name (rus)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Название (рус)',
                    'en' => 'Name (rus)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Название (рус)',
                    'en' => 'Name (rus)',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_NAME_CN',
                'XML_ID' => 'UF_NAME_CN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Название (кит)',
                    'en' => 'Name (cn)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Название (кит)',
                    'en' => 'Name (cn)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Название (кит)',
                    'en' => 'Name (cn)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Название (кит)',
                    'en' => 'Name (cn)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Название (кит)',
                    'en' => 'Name (cn)',
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
                'FIELD_NAME' => 'UF_VIDEO_INDEX',
                'XML_ID' => 'UF_VIDEO_INDEX',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Индекс видео',
                    'en' => 'Video index',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Индекс видео',
                    'en' => 'Video index',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Индекс видео',
                    'en' => 'Video index',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Индекс видео',
                    'en' => 'Video index',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Индекс видео',
                    'en' => 'Video index',
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
                'FIELD_NAME' => 'UF_CAUSE_PARAM_NAME',
                'XML_ID' => 'UF_CAUSE_PARAM_NAME',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Название параметра условия',
                    'en' => 'Name param cause',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Название параметра условия',
                    'en' => 'Name param cause',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Название параметра условия',
                    'en' => 'Name param cause',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Название параметра условия',
                    'en' => 'Name param cause',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Название параметра условия',
                    'en' => 'Name param cause',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_CAUSE_PARAM_VALUE',
                'XML_ID' => 'UF_CAUSE_PARAM_VALUE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Значение параметра условия',
                    'en' => 'Value param cause',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Значение параметра условия',
                    'en' => 'Value param cause',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Значение параметра условия',
                    'en' => 'Value param cause',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Значение параметра условия',
                    'en' => 'Value param cause',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Значение параметра условия',
                    'en' => 'Value param cause',
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
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_VIDEO_START',
                'XML_ID' => 'UF_VIDEO_START',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Время начала видео',
                    'en' => 'Time start video',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Время начала видео',
                    'en' => 'Time start video',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Время начала видео',
                    'en' => 'Time start video',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Время начала видео',
                    'en' => 'Time start video',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Время начала видео',
                    'en' => 'Time start video',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_AUDIO_INDEX',
                'XML_ID' => 'UF_AUDIO_INDEX',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Индекс аудио',
                    'en' => 'Index audio',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Индекс аудио',
                    'en' => 'Index audio',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Индекс аудио',
                    'en' => 'Index audio',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Индекс аудио',
                    'en' => 'Index audio',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Индекс аудио',
                    'en' => 'Index audio',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_AUDIO_START',
                'XML_ID' => 'UF_AUDIO_START',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Время начала аудио',
                    'en' => 'Time start audio',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Время начала аудио',
                    'en' => 'Time start audio',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Время начала аудио',
                    'en' => 'Time start audio',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Время начала аудио',
                    'en' => 'Time start audio',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Время начала аудио',
                    'en' => 'Time start audio',
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
     */
    public function down()
    {
        $highloadBlockId = HLblock::getByTableName('tracing_chapter')["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}

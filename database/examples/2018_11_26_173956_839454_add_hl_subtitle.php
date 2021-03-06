<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddHlSubtitle20181126173956839454 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $fields = [
            'NAME' => 'TracingSubtitle',
            'TABLE_NAME' => 'tracing_subtitle',
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
            "NAME" => 'Субтитры для трейсинга',
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
                    'ru' => 'Текст субтитров (англ)',
                    'en' => 'Text subtitle (eng)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Текст субтитров (англ)',
                    'en' => 'Text subtitle (eng)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Текст субтитров (англ)',
                    'en' => 'Text subtitle (eng)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Текст субтитров (англ)',
                    'en' => 'Text subtitle (eng)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Текст субтитров (англ)',
                    'en' => 'Text subtitle (eng)',
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
                    'ru' => 'Текст субтитров (рус)',
                    'en' => 'Text subtitle (rus)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Текст субтитров (рус)',
                    'en' => 'Text subtitle (rus)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Текст субтитров (рус)',
                    'en' => 'Text subtitle (rus)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Текст субтитров (рус)',
                    'en' => 'Text subtitle (rus)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Текст субтитров (рус)',
                    'en' => 'Text subtitle (rus)',
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
                    'ru' => 'Текст субтитров (кит)',
                    'en' => 'Text subtitle (cn)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Текст субтитров (кит)',
                    'en' => 'Text subtitle (cn)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Текст субтитров (кит)',
                    'en' => 'Text subtitle (cn)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Текст субтитров (кит)',
                    'en' => 'Text subtitle (cn)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Текст субтитров (кит)',
                    'en' => 'Text subtitle (cn)',
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
                'FIELD_NAME' => 'UF_ADD_C_PARAM_NAME',
                'XML_ID' => 'UF_ADD_C_PARAM_NAME',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Название доп. параметра условия',
                    'en' => 'Name param cause',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Название доп. параметра условия',
                    'en' => 'Name param cause',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Название доп. параметра условия',
                    'en' => 'Name param cause',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Название доп. параметра условия',
                    'en' => 'Name param cause',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Название доп. параметра условия',
                    'en' => 'Name param cause',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_ADD_C_PARAM_VALUE',
                'XML_ID' => 'UF_ADD_C_PARAM_VALUE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Значение доп. параметра условия',
                    'en' => 'Value param cause',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Значение доп. параметра условия',
                    'en' => 'Value param cause',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Значение доп. параметра условия',
                    'en' => 'Value param cause',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Значение доп. параметра условия',
                    'en' => 'Value param cause',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Значение доп. параметра условия',
                    'en' => 'Value param cause',
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
        $highloadBlockId = HLblock::getByTableName('tracing_subtitle')["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}

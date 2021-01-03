<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddHlStoneLocation20181126212222370767 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $fields = [
            'NAME' => 'StoneLocation',
            'TABLE_NAME' => 'stone_location',
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
            "NAME" => 'Месторождения',
        ]);

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors));
        }

        $fields = [
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
                'FIELD_NAME' => 'UF_LAT',
                'XML_ID' => 'UF_LAT',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Широта',
                    'en' => 'Lattitude',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Широта',
                    'en' => 'Lattitude',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Широта',
                    'en' => 'Lattitude',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Широта',
                    'en' => 'Lattitude',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Широта',
                    'en' => 'Lattitude',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_LON',
                'XML_ID' => 'UF_LON',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Долгота',
                    'en' => 'Longtitude',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Долгота',
                    'en' => 'Longtitude',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Долгота',
                    'en' => 'Longtitude',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Долгота',
                    'en' => 'Longtitude',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Долгота',
                    'en' => 'Longtitude',
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
        $highloadBlockId = HLblock::getByTableName('stone_location')["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}

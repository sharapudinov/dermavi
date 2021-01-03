<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddUserDocumentsHlblock20190625114637970013 extends BitrixMigration
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
            'NAME' => 'UserDocument',
            'TABLE_NAME' => 'app_user_document',
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
            "NAME" => 'Документы пользователей',
        ]);

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors));
        }

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_USER_ID',
                'XML_ID' => 'UF_USER_ID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Идентификатор пользователя',
                    'en' => 'User ID',
                ],
                'LIST_COLUMN_LABEL' => [
                    'ru' => 'Идентификатор пользователя',
                    'en' => 'User ID',
                ],
                'LIST_FILTER_LABEL' => [
                    'ru' => 'Идентификатор пользователя',
                    'en' => 'User ID',
                ],
                'ERROR_MESSAGE' => [
                    'ru' => 'Идентификатор пользователя',
                    'en' => 'User ID',
                ],
                'HELP_MESSAGE' => [
                    'ru' => 'Идентификатор пользователя',
                    'en' => 'User ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_STATUS',
                'XML_ID' => 'UF_STATUS',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Статус',
                    'en' => 'Status',
                ],
                'LIST_COLUMN_LABEL' => [
                    'ru' => 'Статус',
                    'en' => 'Status',
                ],
                'LIST_FILTER_LABEL' => [
                    'ru' => 'Статус',
                    'en' => 'Status',
                ],
                'ERROR_MESSAGE' => [
                    'ru' => 'Статус',
                    'en' => 'Status',
                ],
                'HELP_MESSAGE' => [
                    'ru' => 'Статус',
                    'en' => 'Status',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_CODE',
                'XML_ID' => 'UF_CODE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Код документа',
                    'en' => 'Document code',
                ],
                'LIST_COLUMN_LABEL' => [
                    'ru' => 'Код документа',
                    'en' => 'Document code',
                ],
                'LIST_FILTER_LABEL' => [
                    'ru' => 'Код документа',
                    'en' => 'Document code',
                ],
                'ERROR_MESSAGE' => [
                    'ru' => 'Код документа',
                    'en' => 'Document code',
                ],
                'HELP_MESSAGE' => [
                    'ru' => 'Код документа',
                    'en' => 'Document code',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_DATE_CREATE',
                'XML_ID' => 'UF_DATE_CREATE',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Дата создания',
                    'en' => 'Date create',
                ],
                'LIST_COLUMN_LABEL' => [
                    'ru' => 'Дата создания',
                    'en' => 'Date create',
                ],
                'LIST_FILTER_LABEL' => [
                    'ru' => 'Дата создания',
                    'en' => 'Date create',
                ],
                'ERROR_MESSAGE' => [
                    'ru' => 'Дата создания',
                    'en' => 'Date create',
                ],
                'HELP_MESSAGE' => [
                    'ru' => 'Дата создания',
                    'en' => 'Date create',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_DATE_UPDATE',
                'XML_ID' => 'UF_DATE_UPDATE',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Дата обновления',
                    'en' => 'Date update',
                ],
                'LIST_COLUMN_LABEL' => [
                    'ru' => 'Дата обновления',
                    'en' => 'Date update',
                ],
                'LIST_FILTER_LABEL' => [
                    'ru' => 'Дата обновления',
                    'en' => 'Date update',
                ],
                'ERROR_MESSAGE' => [
                    'ru' => 'Дата обновления',
                    'en' => 'Date update',
                ],
                'HELP_MESSAGE' => [
                    'ru' => 'Дата обновления',
                    'en' => 'Date update',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_FILES',
                'XML_ID' => 'UF_FILES',
                'USER_TYPE_ID' => 'string',
                'MULTIPLE' => 'Y',
                'MANDATORY' => 'Y',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Файлы',
                    'en' => 'Files',
                ],
                'LIST_COLUMN_LABEL' => [
                    'ru' => 'Файлы',
                    'en' => 'Files',
                ],
                'LIST_FILTER_LABEL' => [
                    'ru' => 'Файлы',
                    'en' => 'Files',
                ],
                'ERROR_MESSAGE' => [
                    'ru' => 'Файлы',
                    'en' => 'Files',
                ],
                'HELP_MESSAGE' => [
                    'ru' => 'Файлы',
                    'en' => 'Files',
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
        $highloadBlockId = HLblock::getByTableName('app_user_document')["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}

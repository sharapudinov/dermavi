<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\SystemException;

/** @noinspection PhpUnused */

class AddHlCommonLog20200918171007644007 extends BitrixMigration
{
    public const HL_NAME = 'CommonLog';
    public const HL_TABLE_NAME = 'app_common_log';
    public const HL_NAME_RU = 'Общий лог';

    /**
     * Run the migration.
     *
     * @return bool
     * @throws MigrationException
     * @throws SystemException
     * @throws Exception
     */
    public function up(): bool
    {
        $fields = [
            'NAME' => self::HL_NAME,
            'TABLE_NAME' => self::HL_TABLE_NAME,
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
            'ID' => $highloadBlockId,
            'LID' => 'ru',
            'NAME' => self::HL_NAME_RU,
        ]);

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException(
                sprintf(
                    'Ошибка при добавлении языкового названия для hl-блока %s: %s',
                    self::HL_NAME,
                    implode(', ', $errors)
                )
            );
        }

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_DATE_CREATE',
                'XML_ID' => 'UF_DATE_CREATE',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY' => 'Y',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Дата создания',
                        'en' => 'Date create',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Дата создания',
                        'en' => 'Date create',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Дата создания',
                        'en' => 'Date create',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Дата создания',
                        'en' => 'Date create',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Дата создания',
                        'en' => 'Date create',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_ENTITY_TYPE',
                'XML_ID' => 'UF_ENTITY_TYPE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Тип сущности',
                        'en' => 'Entity type',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Тип сущности',
                        'en' => 'Entity type',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Тип сущности',
                        'en' => 'Entity type',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Тип сущности',
                        'en' => 'Entity type',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Тип сущности',
                        'en' => 'Entity type',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_ENTITY_ID',
                'XML_ID' => 'UF_ENTITY_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'ID сущности',
                        'en' => 'Entity id',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'ID сущности',
                        'en' => 'Entity id',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'ID сущности',
                        'en' => 'Entity id',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'ID сущности',
                        'en' => 'Entity id',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'ID сущности',
                        'en' => 'Entity id',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_MESSAGE_TYPE',
                'XML_ID' => 'UF_MESSAGE_TYPE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Тип сообщения',
                        'en' => 'Message type',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Тип сообщения',
                        'en' => 'Message type',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Тип сообщения',
                        'en' => 'Message type',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Тип сообщения',
                        'en' => 'Message type',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Тип сообщения',
                        'en' => 'Message type',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_MESSAGE',
                'XML_ID' => 'UF_MESSAGE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'SETTINGS' => [
                    'SIZE' => 80,
                ],
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Сообщение',
                        'en' => 'Message',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Сообщение',
                        'en' => 'Message',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Сообщение',
                        'en' => 'Message',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Сообщение',
                        'en' => 'Message',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Сообщение',
                        'en' => 'Message',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_ADDITIONAL_DATA',
                'XML_ID' => 'UF_DATA',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'SETTINGS' => [
                    'SIZE' => 80,
                    'ROWS' => 3,
                ],
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Дополнительные данные',
                        'en' => 'Additional data',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Дополнительные данные',
                        'en' => 'Additional data',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Дополнительные данные',
                        'en' => 'Additional data',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Дополнительные данные',
                        'en' => 'Additional data',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Дополнительные данные',
                        'en' => 'Additional data',
                    ],
            ],
        ];

        foreach ($fields as $field) {
            $this->addUF($field);
        }

        return true;
    }

    /**
     * Reverse the migration.
     *
     * @return bool
     * @throws MigrationException
     */
    public function down(): bool
    {
        $highloadBlockId = HLblock::getByTableName(self::HL_TABLE_NAME)['ID'];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }

        return true;
    }
}

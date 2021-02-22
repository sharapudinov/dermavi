<?php

use App\Models\Catalog\HL\Office;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Добавляет справочник локаций бриллиантов.
 *
 * Class AddOfficesDictionary20200826195354605614
 */
class AddOfficesDictionary20200826195354605614 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     */
    public function up(): void
    {
        //Создаем HL-блок
        $fields = [
            'NAME'       => ucfirst(Office::getDictID()),
            'TABLE_NAME' => Office::getTableName(),
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
            $result = HighloadBlockLangTable::add(
                [
                    "ID"   => $highloadBlockId,
                    "LID"  => "ru",
                    "NAME" => $dict['NAME_RU'],
                ]
            );
        }

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException(
                'Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors)
            );
        }

        $fields = [
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_XML_ID',
                'XML_ID'            => 'UF_XML_ID',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'Y',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Идентификатор значения',
                        'en' => 'ID value',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Идентификатор значения',
                        'en' => 'ID value',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Идентификатор значения',
                        'en' => 'ID value',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Идентификатор значения',
                        'en' => 'ID value',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Идентификатор значения',
                        'en' => 'ID value',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_NAME',
                'XML_ID'            => 'UF_NAME',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
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
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Значение',
                        'en' => 'Value',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_DISPLAY_VALUE_EN',
                'XML_ID'            => 'UF_DISPLAY_VALUE_EN',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Значение (англ)',
                        'en' => 'Value (eng)',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение (англ)',
                        'en' => 'Value (eng)',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение (англ)',
                        'en' => 'Value (eng)',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Значение (англ)',
                        'en' => 'Value',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Значение (англ)',
                        'en' => 'Value (eng)',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_DISPLAY_VALUE_RU',
                'XML_ID'            => 'UF_DISPLAY_VALUE_RU',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Значение (рус)',
                        'en' => 'Value (rus)',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение (рус)',
                        'en' => 'Value (rus)',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение (рус)',
                        'en' => 'Value (rus)',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Значение (рус)',
                        'en' => 'Value',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Значение (рус)',
                        'en' => 'Value (rus)',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_DISPLAY_VALUE_CN',
                'XML_ID'            => 'UF_DISPLAY_VALUE_CN',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Значение (кит)',
                        'en' => 'Value (cn)',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение (кит)',
                        'en' => 'Value (cn)',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение (кит)',
                        'en' => 'Value (cn)',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Значение (кит)',
                        'en' => 'Value',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Значение (кит)',
                        'en' => 'Value (cn)',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_ADDITIONAL_VALUE',
                'XML_ID'            => 'UF_ADDITIONAL_VALUE',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Дополнительное значение',
                        'en' => 'Additional value',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Дополнительное значение',
                        'en' => 'Additional value',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Дополнительное значение',
                        'en' => 'Additional value',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Дополнительное значение',
                        'en' => 'Additional value',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Дополнительное значение',
                        'en' => 'Additional value',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_SORT',
                'XML_ID'            => 'UF_SORT',
                'USER_TYPE_ID'      => 'integer',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Порядок сортировки',
                        'en' => 'Sort',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Порядок сортировки',
                        'en' => 'Sort',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Порядок сортировки',
                        'en' => 'Sort',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Порядок сортировки',
                        'en' => 'Sort',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Порядок сортировки',
                        'en' => 'Sort',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_DATE_CREATE',
                'XML_ID'            => 'UF_DATE_CREATE',
                'USER_TYPE_ID'      => 'datetime',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
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
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Дата создания',
                        'en' => 'Date create',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Дата создания',
                        'en' => 'Date create',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_DATE_UPDATE',
                'XML_ID'            => 'UF_DATE_UPDATE',
                'USER_TYPE_ID'      => 'datetime',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Дата обновления',
                        'en' => 'Date update',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Дата обновления',
                        'en' => 'Date update',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Дата обновления',
                        'en' => 'Date update',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Дата обновления',
                        'en' => 'Date update',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Дата обновления',
                        'en' => 'Date update',
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
     * @throws \Exception
     */
    public function down(): void
    {
        $highloadBlockId = HLblock::getByTableName(Office::getTableName())["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}

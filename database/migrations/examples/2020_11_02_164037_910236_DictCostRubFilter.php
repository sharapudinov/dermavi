<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class DictCostRubFilter20201102164037910236 extends BitrixMigration
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
            'NAME'       => 'DictCostRubFilter',
            'TABLE_NAME' => 'dict_cost_rub_filter',
        ];
        $result = HighloadBlockTable::add($fields);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении hl-блока: ' . implode(', ', $errors));
        }

        $highloadBlockId = $result->getId();
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        //Добавляем языковые названия для HL-блока
        $result = HighloadBlockLangTable::add(
            [
                "ID"   => $highloadBlockId,
                "LID"  => "ru",
                "NAME" => 'Диапазон цен в рублях',
            ]
        );

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException(
                'Ошибка при добавлении языкового названия для hl-блока DictWeightFilter: ' . implode(', ', $errors)
            );
        }

        $fields = [
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'SORT'              => 1,
                'FIELD_NAME'        => 'UF_ACTIVE',
                'XML_ID'            => 'UF_ACTIVE',
                'USER_TYPE_ID'      => 'boolean',
                'MANDATORY'         => 'N',
                'SETTINGS'          => [
                    'DEFAULT_VALUE' => 1,
                ],
                'EDIT_FORM_LABEL'   =>
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
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Активность',
                        'en' => 'Active',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Активность',
                        'en' => 'Active',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'SORT'              => 2,
                'FIELD_NAME'        => 'UF_SORT',
                'XML_ID'            => 'UF_SORT',
                'USER_TYPE_ID'      => 'integer',
                'MANDATORY'         => 'N',
                'SETTINGS'          => [
                    'DEFAULT_VALUE' => 1,
                ],
                'EDIT_FORM_LABEL'   =>
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
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Сортировка',
                        'en' => 'Sort',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Сортировка',
                        'en' => 'Sort',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'SORT'              => 3,
                'FIELD_NAME'        => 'UF_DISPLAY_VALUE_RU',
                'XML_ID'            => 'UF_DISPLAY_VALUE_RU',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'Y',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Значение (рус)',
                        'en' => 'Значение (рус)',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение (рус)',
                        'en' => 'Значение (рус)',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение (рус)',
                        'en' => 'Значение (рус)',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Значение (рус)',
                        'en' => 'Значение (рус)',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Значение (рус)',
                        'en' => 'Значение (рус)',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'SORT'              => 4,
                'FIELD_NAME'        => 'UF_DISPLAY_VALUE_EN',
                'XML_ID'            => 'UF_DISPLAY_VALUE_EN',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'Y',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Значение (англ)',
                        'en' => 'Значение (англ)',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение (англ)',
                        'en' => 'Значение (англ)',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение (англ)',
                        'en' => 'Значение (англ)',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Значение (англ)',
                        'en' => 'Значение (англ)',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Значение (англ)',
                        'en' => 'Значение (англ)',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'SORT'              => 5,
                'FIELD_NAME'        => 'UF_DISPLAY_VALUE_CN',
                'XML_ID'            => 'UF_DISPLAY_VALUE_CN',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'Y',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Значение (кит)',
                        'en' => 'Значение (кит)',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение (кит)',
                        'en' => 'Значение (кит)',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение (кит)',
                        'en' => 'Значение (кит)',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Значение (кит)',
                        'en' => 'Значение (кит)',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Значение (кит)',
                        'en' => 'Значение (кит)',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'SORT'              => 6,
                'FIELD_NAME'        => 'UF_VALUE_FROM',
                'XML_ID'            => 'UF_VALUE_FROM',
                'USER_TYPE_ID'      => 'double',
                'MANDATORY'         => 'Y',
                'SETTINGS'          => [
                    'DEFAULT_VALUE' => 0,
                    'PRECISION'     => 2,
                ],
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Значение от',
                        'en' => 'Value from',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение от',
                        'en' => 'Value from',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение от',
                        'en' => 'Value from',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Значение от',
                        'en' => 'Value from',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Значение от',
                        'en' => 'Value from',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'SORT'              => 7,
                'FIELD_NAME'        => 'UF_VALUE_TO',
                'XML_ID'            => 'UF_VALUE_TO',
                'USER_TYPE_ID'      => 'double',
                'MANDATORY'         => 'Y',
                'SETTINGS'          => [
                    'DEFAULT_VALUE' => 0,
                    'PRECISION'     => 2,
                ],
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Значение до',
                        'en' => 'Value from',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Значение до',
                        'en' => 'Value to',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Значение до',
                        'en' => 'Value to',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Значение до',
                        'en' => 'Value to',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Значение до',
                        'en' => 'Value to',
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
        $highloadBlockId = HLblock::getByTableName('dict_cost_rub_filter')['ID'];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}

<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpdateHlCaptions20181211185853273709 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $highloadBlockId = HLblock::getByTableName('tracing_caption')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
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
        $highloadBlockId = HLblock::getByTableName('tracing_caption')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            'UF_CAUSE_PARAM_NAME',
            'UF_CAUSE_PARAM_VALUE',
            'UF_ADD_C_PARAM_NAME',
            'UF_ADD_C_PARAM_VALUE',
        ];
        $res = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
            ]
        );
        while ($field = $res->Fetch()) {
            if (!in_array($field['FIELD_NAME'], $fields)) {
                continue;
            }
            (new CUserTypeEntity)->Delete($field['ID']);
        }
    }
}

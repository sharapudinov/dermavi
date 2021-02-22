<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpdHlViewingRequestForm20190130102116448623 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $highloadBlockId = HLblock::getByTableName('app_viewing_request_form')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_URL_DIAMOND',
                'XML_ID' => 'UF_URL_DIAMOND',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ссылка на бриллиант',
                    'en' => 'URL diamond',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ссылка на бриллиант',
                    'en' => 'URL diamond',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ссылка на бриллиант',
                    'en' => 'URL diamond',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ссылка на бриллиант',
                    'en' => 'URL diamond',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ссылка на бриллиант',
                    'en' => 'URL diamond',
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
        $highloadBlockId = HLblock::getByTableName('app_viewing_request_form')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            'UF_URL_DIAMOND',
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

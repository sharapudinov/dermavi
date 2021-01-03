<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpHlAudio20181229020853519649 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $highloadBlockId = HLblock::getByTableName('tracing_audio_part')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_DURATION',
                'XML_ID' => 'UF_DURATION',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Длительность',
                    'en' => 'Duration',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Длительность',
                    'en' => 'Duration',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Длительность',
                    'en' => 'Duration',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Длительность',
                    'en' => 'Duration',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Длительность',
                    'en' => 'Duration',
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
        $highloadBlockId = HLblock::getByTableName('tracing_audio_part')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            'UF_DURATION',
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

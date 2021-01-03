<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpdateHlDiamondPacket20181210231645482826 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $highloadBlockId = HLblock::getByTableName('diamond_packet')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_STONE_GUID',
                'XML_ID' => 'UF_STONE_GUID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Идентификатор алмаза',
                    'en' => 'Stone GUID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Идентификатор алмаза',
                    'en' => 'Stone GUID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Идентификатор алмаза',
                    'en' => 'Stone GUID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Идентификатор алмаза',
                    'en' => 'Stone GUID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Идентификатор алмаза',
                    'en' => 'Stone GUID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_HAS_WIDGET',
                'XML_ID' => 'UF_HAS_WIDGET',
                'USER_TYPE_ID' => 'boolean',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Имеет виджет',
                    'en' => 'Has widget',
                ],
                'LIST_COLUMN_LABEL' => [
                    'ru' => 'Имеет виджет',
                    'en' => 'Has widget',
                ],
                'LIST_FILTER_LABEL' => [
                    'ru' => 'Имеет виджет',
                    'en' => 'Has widget',
                ],
                'ERROR_MESSAGE' => [
                    'ru' => 'Имеет виджет',
                    'en' => 'Has widget',
                ],
                'HELP_MESSAGE' => [
                    'ru' => 'Имеет виджет',
                    'en' => 'Has widget',
                ],
            ]
        ];

        foreach ($fields as $field) {
            $this->addUF($field);
        }

        $highloadBlockId = HLblock::getByTableName('packet_additional_info')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;
        $fields = ['UF_PACKET_GUID'];
        $this->deleteFields($fields, $highloadBlockEntityId);
    }

    private function deleteFields(array $fields, string $highloadBlockEntityId)
    {
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

    /**
     * Reverse the migration.
     *
     * @return mixed
     */
    public function down()
    {
        $highloadBlockId = HLblock::getByTableName('diamond_packet')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = ['UF_STONE_GUID'];
        $this->deleteFields($fields, $highloadBlockEntityId);
    }
}

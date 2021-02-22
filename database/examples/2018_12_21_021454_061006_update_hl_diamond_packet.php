<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpdateHlDiamondPacket20181221021454061006 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $highloadBlockId = HLblock::getByTableName('diamond_packet')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_HAS_WIDGET',
                'XML_ID' => 'UF_HAS_WIDGET',
                'USER_TYPE_ID' => 'boolean',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Имеет виджет',
                    'en' => 'Has widget',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Имеет виджет',
                    'en' => 'Has widget',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Имеет виджет',
                    'en' => 'Has widget',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Имеет виджет',
                    'en' => 'Has widget',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Имеет виджет',
                    'en' => 'Has widget',
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
        $highloadBlockId = HLblock::getByTableName('diamond_packet')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = ['UF_HAS_WIDGET'];
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

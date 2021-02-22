<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpdateHlDiamondPacket20181211185404553275 extends BitrixMigration
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
                'FIELD_NAME' => 'UF_NAME',
                'XML_ID' => 'UF_NAME',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Имя бриллианта',
                    'en' => 'Diamond name',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Имя бриллианта',
                    'en' => 'Diamond name',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Имя бриллианта',
                    'en' => 'Diamond name',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Имя бриллианта',
                    'en' => 'Diamond name',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Имя бриллианта',
                    'en' => 'Diamond name',
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
        $highloadBlockId = HLblock::getByTableName('diamond_packet')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = ['UF_NAME'];
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

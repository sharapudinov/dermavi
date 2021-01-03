<?php

use App\Models\Catalog\HL\Factory;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpdateHlFactory20181212151041693888 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $highloadBlockId = HLblock::getByTableName(Factory::getTableName())["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $hlStoneLocationID = HLblock::getByTableName('stone_location')["ID"];
        $hlStoneLocationEntityId = 'HLBLOCK_' . $hlStoneLocationID;
        $showField = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => $hlStoneLocationEntityId,
                'FIELD_NAME' => 'UF_NAME_RU',
            ]
        )->Fetch();

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_LOCATION',
                'XML_ID' => 'UF_LOCATION',
                'USER_TYPE_ID' => 'hlblock',
                'MANDATORY' => 'N',
                'SETTINGS' => [
                    'HLBLOCK_ID' => $hlStoneLocationID,
                    'HLFIELD_ID' => $showField['ID'],
                ],
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Место огранки',
                    'en' => 'Cutting place',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Место огранки',
                    'en' => 'Cutting place',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Место огранки',
                    'en' => 'Cutting place',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Место огранки',
                    'en' => 'Cutting place',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Место огранки',
                    'en' => 'Cutting place',
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
        $highloadBlockId = HLblock::getByTableName(Factory::getTableName())["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = ['UF_LOCATION'];
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

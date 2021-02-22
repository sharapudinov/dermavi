<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixIblockHelper\HLblock;


class HlDiamondAddOfficeGuid20200827024240787253 extends BitrixMigration
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
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_OFFICE_GUID',
                'XML_ID'            => 'UF_OFFICE_GUID',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Идентификатор локации',
                        'en' => 'Office GUID',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Идентификатор локации',
                        'en' => 'Office GUID',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Идентификатор локации',
                        'en' => 'Office GUID',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Идентификатор алмаза',
                        'en' => 'Office GUID',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Идентификатор алмаза',
                        'en' => 'Office GUID',
                    ],
            ]
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

        $fields = ['UF_OFFICE_GUID'];
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


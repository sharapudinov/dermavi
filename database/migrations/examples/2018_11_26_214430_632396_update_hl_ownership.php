<?php

use App\Models\Catalog\HL\Ownership;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpdateHlOwnership20181126214430632396 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $highloadBlockId = HLblock::getByTableName(Ownership::getTableName())["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $hlStoneLocationID = HLblock::getByTableName('stone_location')["ID"];
        $hlStoneLocationEntityId = 'HLBLOCK_' . $hlStoneLocationID;
        $showField = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => hlStoneLocationEntityId,
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
                    'ru' => 'Месторождение',
                    'en' => 'Origin',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Месторождение',
                    'en' => 'Origin',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Месторождение',
                    'en' => 'Origin',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Месторождение',
                    'en' => 'Origin',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Месторождение',
                    'en' => 'Origin',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_COEFFICIENT',
                'XML_ID' => 'UF_COEFFICIENT',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Коэффициент',
                    'en' => 'Coefficient',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Коэффициент',
                    'en' => 'Coefficient',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Коэффициент',
                    'en' => 'Coefficient',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Коэффициент',
                    'en' => 'Coefficient',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Коэффициент',
                    'en' => 'Coefficient',
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
        $highloadBlockId = HLblock::getByTableName(Ownership::getTableName())["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = ['UF_LOCATION', 'UF_COEFFICIENT'];
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

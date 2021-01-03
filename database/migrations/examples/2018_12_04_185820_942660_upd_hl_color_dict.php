<?php

use App\Models\Catalog\HL\Color;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpdHlColorDict20181204185820942660 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $highloadBlockId = HLblock::getByTableName(Color::getTableName())["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_GROUP',
                'XML_ID' => 'UF_GROUP',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Цветовая группа',
                    'en' => 'Color group',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Цветовая группа',
                    'en' => 'Color group',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Цветовая группа',
                    'en' => 'Color group',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Цветовая группа',
                    'en' => 'Color group',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Цветовая группа',
                    'en' => 'Color group',
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
        $highloadBlockId = HLblock::getByTableName(Color::getTableName())["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = ['UF_GROUP'];
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

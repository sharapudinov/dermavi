<?php

use App\Models\Catalog\HL\Ownership;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpdateHlOwnership20181211162532944746 extends BitrixMigration
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

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_AGE_STONE',
                'XML_ID' => 'UF_AGE_STONE',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Возраст камней (млн. лет)',
                    'en' => 'Age stone',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Возраст камней (млн. лет)',
                    'en' => 'Age stone',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Возраст камней (млн. лет)',
                    'en' => 'Age stone',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Возраст камней (млн. лет)',
                    'en' => 'Age stone',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Возраст камней (млн. лет)',
                    'en' => 'Age stone',
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

        $fields = ['UF_AGE_STONE'];
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

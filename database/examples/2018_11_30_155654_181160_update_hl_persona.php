<?php

use App\Models\Catalog\HL\Persona;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpdateHlPersona20181130155654181160 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $highloadBlockId = HLblock::getByTableName(Persona::getTableName())["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_WORK_EXP',
                'XML_ID' => 'UF_WORK_EXP',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Стаж работы',
                    'en' => 'Work experience',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Стаж работы',
                    'en' => 'Work experience',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Стаж работы',
                    'en' => 'Work experience',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Стаж работы',
                    'en' => 'Work experience',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Стаж работы',
                    'en' => 'Work experience',
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
        $highloadBlockId = HLblock::getByTableName(Persona::getTableName())["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = ['UF_WORK_EXP'];
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

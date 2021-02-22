<?php

use App\Models\Catalog\HL\Fluor;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpdateHlFluors20181207014003587603 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $highloadBlockId = HLblock::getByTableName(Fluor::getTableName())["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_INTECITY',
                'XML_ID' => 'UF_INTECITY',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Интенсивность',
                    'en' => 'Intencity',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Интенсивность',
                    'en' => 'Intencity',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Интенсивность',
                    'en' => 'Intencity',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Интенсивность',
                    'en' => 'Intencity',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Интенсивность',
                    'en' => 'Intencity',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_COLOR',
                'XML_ID' => 'UF_COLOR',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Цвет',
                    'en' => 'Color',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Цвет',
                    'en' => 'Color',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Цвет',
                    'en' => 'Color',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Цвет',
                    'en' => 'Color',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Цвет',
                    'en' => 'Color',
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
        $highloadBlockId = HLblock::getByTableName(Fluor::getTableName())["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = ['UF_INTECITY', 'UF_COLOR'];
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

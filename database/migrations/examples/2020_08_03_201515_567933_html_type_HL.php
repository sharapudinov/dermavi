<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

/**
 * Class HtmlTypeHL20200803201515567933
 */
class HtmlTypeHL20200803201515567933 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $highloadBlockEntityId = 'HLBLOCK_';
        
        $fields = [
            [
            'ENTITY_ID' => $highloadBlockEntityId,
            'FIELD_NAME' => 'UF_DESCR_TEXT',
            'XML_ID' => 'UF_DESCR_TEXT',
            'USER_TYPE_ID' => 'HTML',
            'MANDATORY' => 'N',
            'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Текст',
                    'en' => 'Text',
                ],
            'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Текст',
                    'en' => 'Text',
                ],
            'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Текст',
                    'en' => 'Text',
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
    
        $fields = ['UF_DESCR_TEXT'];
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
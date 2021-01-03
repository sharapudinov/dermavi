<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use \App\Models\HL\ViewingRequestForm;
use \Arrilot\BitrixIblockHelper\HLblock;

class ModifyRequestFormDiamondsField20191002113316281893 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {

        db()->startTransaction();

        // взять все формы
        $list = ViewingRequestForm::getList();

        // удалить поле
        $highloadBlockId = HLblock::getByTableName(ViewingRequestForm::TABLE_CODE)['ID'];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = ['UF_DIAMONDS'];
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

        // добавить поле
        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_DIAMONDS',
                'XML_ID' => 'UF_DIAMONDS',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'SETTINGS' => [
                  'SIZE' => 150,
                  'ROWS' => 25,
                ],
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Идентификаторы бриллиантов',
                        'en' => 'Diamonds packet numbers',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Идентификаторы бриллиантов',
                        'en' => 'Diamonds packet numbers',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Идентификаторы бриллиантов',
                        'en' => 'Diamonds packet numbers',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Идентификаторы бриллиантов',
                        'en' => 'Diamonds packet numbers',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Идентификаторы бриллиантов',
                        'en' => 'Diamonds packet numbers',
                    ],
            ]
        ];

        foreach ($fields as $field) {
            $this->addUF($field);
        }

        // вставить старые значения
        foreach ($list as $row) {
            $id = $row['ID'];
            $value = $row['UF_DIAMONDS'];

            $entity = ViewingRequestForm::getById($id);
            $entity['UF_DIAMONDS'] = ($value . '19003852');
            $entity->save(['UF_DIAMONDS']);
        }

        db()->rollbackTransaction();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        // Это слишком сложно и совсем не нужно.
    }
}

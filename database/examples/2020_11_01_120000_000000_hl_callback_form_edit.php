<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;

class HlCallbackFormEdit20201101120000000000 extends BitrixMigration
{
    public const HL_NAME = 'FormCallBack';

    /**
     * @return void
     * @throws \Exception
     */
    public function up(): void
    {
        Loader::includeModule('highloadblock');

        $hlblock = HighloadBlockTable::getList(['filter' => ['=NAME' => static::HL_NAME]])->fetch();
        if (!$hlblock) {
            throw new MigrationException('Не найден hl-блок: ' . static::HL_NAME);
        }

        $highloadBlockId = $hlblock['ID'];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $newFieldsList = [];
        $newFieldsList[] = [
            'ENTITY_ID' => $highloadBlockEntityId,
            'FIELD_NAME' => 'UF_USER_PHONE',
            'XML_ID' => 'UF_USER_PHONE',
            'USER_TYPE_ID' => 'string',
            'SORT' => 100,
            'MANDATORY' => 'N',
            'MULTIPLE' => 'N',
            'SHOW_FILTER' => 'S', // I - точное совпадение, E - по маске, S - по подстроке
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => [
                'SIZE' => 20,
                'ROWS' => 1,
                'REGEXP' => '',
                'MIN_LENGTH' => 0,
                'MAX_LENGTH' => 0,
                'DEFAULT_VALUE' => '',
            ],
            'EDIT_FORM_LABEL' => [
                'ru' => 'Телефон',
                'en' => 'Телефон',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => 'Телефон',
                'en' => 'Телефон',
            ],
            'LIST_FILTER_LABEL' => [
                'ru' => 'Телефон',
                'en' => 'Телефон',
            ],
            'ERROR_MESSAGE' => [],
            'HELP_MESSAGE' => [],
        ];

        $newFieldsList[] = [
            'ENTITY_ID' => $highloadBlockEntityId,
            'FIELD_NAME' => 'UF_USER_COMMENT',
            'XML_ID' => 'UF_USER_COMMENT',
            'USER_TYPE_ID' => 'string',
            'SORT' => 400,
            'MANDATORY' => 'N',
            'MULTIPLE' => 'N',
            'SHOW_FILTER' => 'N', // I - точное совпадение, E - по маске, S - по подстроке
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => [
                'SIZE' => 100,
                'ROWS' => 6,
                'REGEXP' => '',
                'MIN_LENGTH' => 0,
                'MAX_LENGTH' => 0,
                'DEFAULT_VALUE' => '',
            ],
            'EDIT_FORM_LABEL' => [
                'ru' => 'Комментарий',
                'en' => 'Комментарий',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => 'Комментарий',
                'en' => 'Комментарий',
            ],
            'LIST_FILTER_LABEL' => [
                'ru' => 'Комментарий',
                'en' => 'Комментарий',
            ],
            'ERROR_MESSAGE' => [],
            'HELP_MESSAGE' => [],
        ];

        $editFieldsList = [];
        $editFieldsList[] = [
            'ENTITY_ID' => $highloadBlockEntityId,
            'FIELD_NAME' => 'UF_USER_NAME',
            'SORT' => 200,
            'SHOW_FILTER' => 'S', // I - точное совпадение, E - по маске, S - по подстроке
            'EDIT_FORM_LABEL' => [
                'ru' => 'Имя',
                'en' => 'Имя',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => 'Имя',
                'en' => 'Имя',
            ],
            'LIST_FILTER_LABEL' => [
                'ru' => 'Имя',
                'en' => 'Имя',
            ],
            'ERROR_MESSAGE' => [
                'ru' => '',
                'en' => '',
            ],
            'HELP_MESSAGE' => [
                'ru' => '',
                'en' => '',
            ],
        ];
        $editFieldsList[] = [
            'ENTITY_ID' => $highloadBlockEntityId,
            'FIELD_NAME' => 'UF_USER_EMAIL',
            'SORT' => 300,
            'SHOW_FILTER' => 'S', // I - точное совпадение, E - по маске, S - по подстроке
            'EDIT_FORM_LABEL' => [
                'ru' => 'E-mail',
                'en' => 'E-mail',
            ],
            'LIST_COLUMN_LABEL' => [
                'ru' => 'E-mail',
                'en' => 'E-mail',
            ],
            'LIST_FILTER_LABEL' => [
                'ru' => 'E-mail',
                'en' => 'E-mail',
            ],
            'ERROR_MESSAGE' => [
                'ru' => '',
                'en' => '',
            ],
            'HELP_MESSAGE' => [
                'ru' => '',
                'en' => '',
            ],
        ];

        $deleteFieldsList = [
            $highloadBlockEntityId => [
                'UF_USER_SURNAME', 'UF_USER_THEME', 'UF_USER_QUESTION',
            ],
        ];


        foreach ($newFieldsList as $fields) {
            if ($this->searchField($fields['ENTITY_ID'], $fields['FIELD_NAME'])) {
                continue;
            }
            $this->addUF($fields);
        }

        foreach ($editFieldsList as $fields) {
            $curField = $this->searchField($fields['ENTITY_ID'], $fields['FIELD_NAME']);
            if (!$curField) {
                continue;
            }
            $userTypeEntity = new CUserTypeEntity();
            $userTypeEntity->Update($curField['ID'], $fields);
        }

        foreach ($deleteFieldsList as $entityId => $fields) {
            $this->deleteFields($fields, $entityId);
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function down(): void
    {
        //
    }

    /**
     * @param string $entity
     * @param string $code
     * @return array
     */
    private function searchField(string $entity, string $code)
    {
        $filter = [
            'ENTITY_ID' => $entity,
            'FIELD_NAME' => $code,
        ];

        return CUserTypeEntity::GetList(['ID' => 'ASC'], $filter)->fetch();
    }

    /**
     * @param array $fields
     * @param string $highloadBlockEntityId
     */
    private function deleteFields(array $fields, string $highloadBlockEntityId): void
    {
        $res = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
            ]
        );
        while ($field = $res->Fetch()) {
            if (!in_array($field['FIELD_NAME'], $fields, true)) {
                continue;
            }
            (new CUserTypeEntity())->Delete($field['ID']);
        }
    }
}

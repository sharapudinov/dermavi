<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class SaveParamsHeaderB2b20201009111219870842 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $fields = [
            'NAME'       => 'B2bHeaderFields',
            'TABLE_NAME' => 'adv_b2b_header_fields',
        ];

        $result = HighloadBlockTable::add($fields);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении hl-блока: ' . implode(', ', $errors));
        }

        $highloadBlockId       = $result->getId();
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        //Добавляем языковые названия для HL-блока
        $result = HighloadBlockLangTable::add(
            [
                "ID"   => $highloadBlockId,
                "LID"  => "ru",
                "NAME" => 'Параметры столбцов бриллиантов',
            ]
        );

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException(
                'Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors)
            );
        }

        $fields = [
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_USER_ID',
                'XML_ID'            => 'UF_USER_ID',
                'PROPERTY_TYPE'     => 'S',
                'USER_TYPE_ID'      => 'app_user',
                'SHOW_FILTER'       => 'S',
                'MANDATORY'         => 'N',
                'SORT'              => 100,
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Пользователь',
                        'en' => 'user',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Пользователь',
                        'en' => 'user',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Пользователь',
                        'en' => 'user',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_FIELDS',
                'XML_ID'            => 'UF_FIELDS',
                'USER_TYPE_ID'      => 'string',
                'SHOW_FILTER'       => 'N',
                'MANDATORY'         => 'N',
                'MULTIPLE'          => 'Y',
                'SORT'              => 200,
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Поля',
                        'en' => 'fields',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Поля',
                        'en' => 'fields',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Поля',
                        'en' => 'fields',
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
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {
        //
    }
}

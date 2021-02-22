<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddHlCatalogSize20201119100541934872 extends BitrixMigration
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
            'NAME'       => 'CatalogSize',
            'TABLE_NAME' => 'catalog_size',
        ];
        $result = HighloadBlockTable::add($fields);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении hl-блока: ' . implode(', ', $errors));
        }

        $highloadBlockId = $result->getId();
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        //Добавляем языковые названия для HL-блока
        if (!empty($dict['NAME_RU'])) {
            $result = HighloadBlockLangTable::add(
                [
                    "ID"   => $highloadBlockId,
                    "LID"  => "ru",
                    "NAME" => 'Справочник размеров для каталога',
                ]
            );
        }

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException(
                'Ошибка при добавлении языкового названия для hl-блока ' . $fields['NAME'] . ': ' . implode(
                    ', ',
                    $errors
                )
            );
        }

        $fields = [
            [
                'ENTITY_ID'    => $highloadBlockEntityId,
                'FIELD_NAME'   => 'UF_XML_ID',
                'XML_ID'       => 'UF_XML_ID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY'    => 'Y',
            ],
            [
                'ENTITY_ID'    => $highloadBlockEntityId,
                'FIELD_NAME'   => 'UF_NAME',
                'XML_ID'       => 'UF_NAME',
                'USER_TYPE_ID' => 'string',
                'MANDATORY'    => 'Y',
            ],
            [
                'ENTITY_ID'    => $highloadBlockEntityId,
                'FIELD_NAME'   => 'UF_SORT',
                'XML_ID'       => 'UF_SORT',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY'    => 'Y',
            ],
            [
                'ENTITY_ID'    => $highloadBlockEntityId,
                'FIELD_NAME'   => 'UF_FROM',
                'XML_ID'       => 'UF_FROM',
                'USER_TYPE_ID' => 'double',
                'MANDATORY'    => 'Y',
                'SETTINGS'     =>
                    [
                        'DEFAULT_VALUE' => 0,
                        'PRECISION'     => 2,
                    ],
            ],
            [
                'ENTITY_ID'    => $highloadBlockEntityId,
                'FIELD_NAME'   => 'UF_TO',
                'XML_ID'       => 'UF_TO',
                'USER_TYPE_ID' => 'double',
                'MANDATORY'    => 'Y',
                'SETTINGS'     =>
                    [
                        'DEFAULT_VALUE' => 0,
                        'PRECISION'     => 2,
                    ],
            ],
            [
                'ENTITY_ID'    => $highloadBlockEntityId,
                'FIELD_NAME'   => 'UF_DATE_CREATE',
                'XML_ID'       => 'UF_DATE_CREATE',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY'    => 'N',
                'SETTINGS'     =>
                    [
                        'DEFAULT_VALUE' => [
                            'TYPE' => 'NOW'
                        ],
                    ],
            ],
            [
                'ENTITY_ID'    => $highloadBlockEntityId,
                'FIELD_NAME'   => 'UF_DATE_UPDATE',
                'XML_ID'       => 'UF_DATE_UPDATE',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY'    => 'N',
                'SETTINGS'     =>
                    [
                        'DEFAULT_VALUE' => [
                            'TYPE' => 'NOW'
                        ],
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
        $highloadBlockId = HLblock::getByTableName('catalog_size')['ID'];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}

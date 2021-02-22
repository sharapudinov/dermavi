<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddHlSitemapUrl20201202124317382303 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $fields = [
            'NAME'       => 'SitemapUrl',
            'TABLE_NAME' => 'app_sitemap_url',
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
                    "NAME" => 'Справочник Sitemap',
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
                'FIELD_NAME'   => 'UF_SORT',
                'XML_ID'       => 'UF_SORT',
                'USER_TYPE_ID' => 'integer',
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
                'FIELD_NAME'   => 'UF_URL',
                'XML_ID'       => 'UF_URL',
                'USER_TYPE_ID' => 'string',
                'MANDATORY'    => 'Y',
            ],
            [
                'ENTITY_ID'    => $highloadBlockEntityId,
                'FIELD_NAME'   => 'UF_PARENT_URL',
                'XML_ID'       => 'UF_PARENT_URL',
                'USER_TYPE_ID' => 'string',
            ],
            [
                'ENTITY_ID'    => $highloadBlockEntityId,
                'FIELD_NAME'   => 'UF_PRIORITY',
                'XML_ID'       => 'UF_PRIORITY',
                'USER_TYPE_ID' => 'string',
                'SETTINGS'     =>
                    [
                        'DEFAULT_VALUE' => '0.7',
                    ],
            ],
            [
                'ENTITY_ID'    => $highloadBlockEntityId,
                'FIELD_NAME'   => 'UF_CHANGEFREQ',
                'XML_ID'       => 'UF_CHANGEFREQ',
                'USER_TYPE_ID' => 'string',
                'SETTINGS'     =>
                    [
                        'DEFAULT_VALUE' => 'monthly',
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
     * @throws \Exception
     */
    public function down()
    {
        $highloadBlockId = HLblock::getByTableName('app_sitemap_url')['ID'];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}

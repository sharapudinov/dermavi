<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use App\Models\HL\Country;

class ReplaceCountriesToHighloadblock20190131144426359941 extends BitrixMigration
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
            'NAME' => 'Country',
            'TABLE_NAME' => 'app_country',
        ];
        $result = HighloadBlockTable::add($fields);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении hl-блока: ' . implode(', ', $errors));
        }
        
        $highloadBlockId = $result->getId();
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;
        
        //Добавляем языковые названия для HL-блока
        $result = HighloadBlockLangTable::add([
            "ID" => $highloadBlockId,
            "LID" => "ru",
            "NAME" => 'Страна',
        ]);
        
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors));
        }
        
        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_XML_ID',
                'XML_ID' => 'UF_XML_ID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Внешний код',
                        'en' => 'xml_id',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Внешний код',
                        'en' => 'xml_id',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Внешний код',
                        'en' => 'xml_id',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Внешний код',
                        'en' => 'xml_id',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Внешний код',
                        'en' => 'xml_id',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_NAME_RU',
                'XML_ID' => 'UF_NAME_RU',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Название (рус)',
                        'en' => 'Name (ru)',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Название (рус)',
                        'en' => 'Name (ru)',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Название (рус)',
                        'en' => 'Name (ru)',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Название (рус)',
                        'en' => 'Name (ru)',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Название (рус)',
                        'en' => 'Name (ru)',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_NAME_EN',
                'XML_ID' => 'UF_NAME_EN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Название (англ)',
                        'en' => 'Name (en)',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Название (англ)',
                        'en' => 'Name (en)',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Название (англ)',
                        'en' => 'Name (en)',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Название (англ)',
                        'en' => 'Name (en)',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Название (англ)',
                        'en' => 'Name (en)',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_NAME_CN',
                'XML_ID' => 'UF_NAME_CN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Название (кит)',
                        'en' => 'Name (cn)',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Название (кит)',
                        'en' => 'Name (cn)',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Название (кит)',
                        'en' => 'Name (cn)',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Название (кит)',
                        'en' => 'Name (cn)',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Название (кит)',
                        'en' => 'Name (cn)',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_CRM_ID',
                'XML_ID' => 'UF_CRM_ID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Идентификатор в CRM',
                        'en' => 'CRM ID',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Идентификатор в CRM',
                        'en' => 'CRM ID',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Идентификатор в CRM',
                        'en' => 'CRM ID',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Идентификатор в CRM',
                        'en' => 'CRM ID',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Идентификатор в CRM',
                        'en' => 'CRM ID',
                    ],
            ]
        ];
        
        foreach ($fields as $field) {
            $this->addUF($field);
        }
        
        global $DB;
        $countries = $DB->Query('SELECT * FROM country');
        while ($country = $countries->GetNext()) {
            Country::create([
                'UF_XML_ID' => $country['name_en'],
                'UF_NAME_RU' => $country['name_ru'],
                'UF_NAME_EN' => $country['name_en']
            ]);
        }
        
        //db()->query('DROP TABLE IF EXISTS country');
    }
    
    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        db()->query('CREATE TABLE country (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name_ru VARCHAR(30),
            name_en VARCHAR(30),
            name_cn VARCHAR(30)
        )');
        
        $countries = Country::getList();
        foreach ($countries as $country) {
            db()->query("INSERT INTO country (name_ru, name_en) VALUES ('" . $country->getName('RU') . "', '" . $country->getName('EN') . "')");
        }
        
        $highloadBlockId = HLblock::getByTableName('app_country')["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}

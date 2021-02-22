<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use App\Models\HL\CompanyActivity;
use App\Models\Client\CompanyActivity as iblockCompanyActivity;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use Arrilot\BitrixIblockHelper\HLblock;

class ChangeCompanyActivitiesFromIblockToHighloadBlock20190204165122192151 extends BitrixMigration
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
            'NAME' => 'CompanyActivity',
            'TABLE_NAME' => 'app_company_activity',
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
            "NAME" => 'Сфера деятельности компании',
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
                'MANDATORY' => 'Y',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Внешний код',
                    'en' => 'Xml id',
                    'cn' => 'Xml id'
                ]
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_NAME_RU',
                'XML_ID' => 'UF_NAME_RU',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Название (рус)',
                    'en' => 'Name (ru)',
                    'cn' => 'Name (ru)'
                ]
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_NAME_EN',
                'XML_ID' => 'UF_NAME_EN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Название (англ)',
                    'en' => 'Name (en)',
                    'cn' => 'Name (en)'
                ]
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_NAME_CN',
                'XML_ID' => 'UF_NAME_CN',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Название (кит)',
                    'en' => 'Name (cn)',
                    'cn' => 'Name (cn)'
                ]
            ]
        ];

        foreach ($fields as $field) {
            $this->addUF($field);
        }

        $oldActivities = iblockCompanyActivity::getList();
        foreach ($oldActivities as $oldActivity) {
            CompanyActivity::create([
                'UF_XML_ID' => $oldActivity['PROPERTY_NAME_EN_VALUE'],
                'UF_NAME_RU' => $oldActivity['PROPERTY_NAME_RU_VALUE'],
                'UF_NAME_EN' => $oldActivity['PROPERTY_NAME_EN_VALUE'],
                'UF_NAME_CN' => $oldActivity['PROPERTY_NAME_CN_VALUE'],
            ]);
        }

        //Пока что закомментил, т.к. в мастере должна быть таблица на ИБ
        //(new CIBlockType)->Delete('client');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        /*(new CIBlockType)->Add([
            'ID' => 'client',
            'SECTIONS' => 'N',
            'LANG' => [
                'en' => [
                    'NAME' => 'Client'
                ],
                'ru' => [
                    'NAME' => 'Клиент'
                ],
                'cn' => [
                    'NAME' => 'Client'
                ]
            ]
        ]);

        $iblockId = (new CIBlock)->Add([
            'NAME' => 'Сфера деятельности компании',
            'CODE' => 'company_activity',
            'IBLOCK_TYPE_ID' => 'client',
            'SITE_ID' => ['s1', 's2', 's3']
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название (рус)',
            'CODE' => 'NAME_RU',
            'PROPERTY_TYPE' => 'S',
            'IS_REQUIRED' => 'Y',
            'SORT' => '500',
            'IBLOCK_ID' => $iblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название (англ)',
            'CODE' => 'NAME_EN',
            'PROPERTY_TYPE' => 'S',
            'IS_REQUIRED' => 'Y',
            'SORT' => '501',
            'IBLOCK_ID' => $iblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название (кит)',
            'CODE' => 'NAME_CN',
            'PROPERTY_TYPE' => 'S',
            'IS_REQUIRED' => 'Y',
            'SORT' => '502',
            'IBLOCK_ID' => $iblockId
        ]);

        $query = db()->query('SELECT * FROM app_company_activity');
        while ($activity = $query->fetch()) {
            iblockCompanyActivity::create([
                'NAME' => $activity['UF_NAME_EN'],
                'PROPS' => [
                    'NAME_RU' => $activity['UF_NAME_RU'],
                    'NAME_EN' => $activity['UF_NAME_EN'],
                    'NAME_CN' => $activity['UF_NAME_CN']
                ]
            ]);
        }*/

        $highloadBlockId = HLblock::getByTableName('app_company_activity')["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}

<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class AddUserDocumentsIblock20190426095148829890 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = (new CIBlock)->Add([
            'NAME' => 'Документы для пользователей в личном кабинете',
            'CODE' => 'personal_section_document_kind',
            'IBLOCK_TYPE_ID' => 'client',
            'VERSION' => '2',
            'SITE_ID' => ['s1', 's2', 's3']
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Тип лица',
            'CODE' => 'PERSON_TYPE',
            'SORT' => '499',
            'IS_REQUIRED' => 'Y',
            'PROPERTY_TYPE' => 'L',
            'VALUES' => [
                ['VALUE' => 'Юридическое лицо'],
                ['VALUE' => 'Физическое лицо']
            ],
            'IBLOCK_ID' => $iblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Тип (что надо предоставлять клиенту)',
            'CODE' => 'TYPE',
            'SORT' => '500',
            'IS_REQUIRED' => 'Y',
            'PROPERTY_TYPE' => 'L',
            'VALUES' => [
                ['VALUE' => 'Оригинал'],
                ['VALUE' => 'Электронная копия']
            ],
            'IBLOCK_ID' => $iblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название (рус)',
            'CODE' => 'NAME_RU',
            'SORT' => '501',
            'IS_REQUIRED' => 'Y',
            'IBLOCK_ID' => $iblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название (англ)',
            'CODE' => 'NAME_EN',
            'SORT' => '502',
            'IS_REQUIRED' => 'Y',
            'IBLOCK_ID' => $iblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название (кит)',
            'CODE' => 'NAME_CN',
            'SORT' => '503',
            'IBLOCK_ID' => $iblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Описание (рус)',
            'CODE' => 'DESCRIPTION_RU',
            'SORT' => '504',
            'IBLOCK_ID' => $iblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Описание (англ)',
            'CODE' => 'DESCRIPTION_EN',
            'SORT' => '505',
            'IBLOCK_ID' => $iblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Описание (кит)',
            'CODE' => 'DESCRIPTION_CN',
            'SORT' => '506',
            'IBLOCK_ID' => $iblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Файл (рус)',
            'CODE' => 'FILE_RU',
            'SORT' => '507',
            'PROPERTY_TYPE' => 'F',
            'IBLOCK_ID' => $iblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Файл (англ)',
            'CODE' => 'FILE_EN',
            'PROPERTY_TYPE' => 'F',
            'SORT' => '508',
            'IBLOCK_ID' => $iblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Файл (кит)',
            'CODE' => 'FILE_CN',
            'PROPERTY_TYPE' => 'F',
            'SORT' => '509',
            'IBLOCK_ID' => $iblockId
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->getIblockIdByCode('personal_section_document_kind');
    }
}

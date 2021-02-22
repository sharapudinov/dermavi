<?php

use App\Models\Banners\PhysicBanner;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddBannersForUsers20200930081443787871 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $ib = new \CIBlock;
        $iblockId = $ib->Add([
            "NAME" => "Баннеры для юридических лиц",
            "CODE" => "banners_legal_users",
            "SITE_ID" => ['s1','s2','s3'],
            "IBLOCK_TYPE_ID" => "banners",
            'IS_CATALOG' => 'N',
            "VERSION" => 2,
            'GROUP_ID' => ['2' => 'R'],
        ]);

        if (!$iblockId) {
            throw new MigrationException('Ошибка при добавлении инфоблока ' . $ib->LAST_ERROR);
        }

        $props = [
            [
                "NAME" => "Заголовок (рус)",
                "SORT" => "100",
                "CODE" => "TITLE_RU",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Заголовок (анг)",
                "SORT" => "100",
                "CODE" => "TITLE_EN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Заголовок (кит)",
                "SORT" => "100",
                "CODE" => "TITLE_CN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Превью текст (рус)",
                "SORT" => "100",
                "CODE" => "PREVIEW_TEXT_RU",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Превью текст (англ)",
                "SORT" => "100",
                "CODE" => "PREVIEW_TEXT_EN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Превью текст (кит)",
                "SORT" => "100",
                "CODE" => "PREVIEW_TEXT_CN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Изображение для баннера (480w)",
                "CODE" => "IMAGE_480",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Изображение для баннера (768w)",
                "CODE" => "IMAGE_768",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Изображение для баннера (1024w)",
                "CODE" => "IMAGE_1024",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Изображение для баннера (1440w)",
                "CODE" => "IMAGE_1440",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Ссылка на источник баннера",
                "SORT" => "100",
                "CODE" => "LINK",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ]
        ];

        foreach ($props as $prop) {
            $this->addIblockElementProperty($prop);
        }

        $ib = new \CIBlock;
        $iblockIdNaturalUser = $ib->Add([
            "NAME" => "Баннеры для физических лиц",
            "CODE" => "banners_natural_users",
            "SITE_ID" => ['s1','s2','s3'],
            "IBLOCK_TYPE_ID" => "banners",
            'IS_CATALOG' => 'N',
            "VERSION" => 2,
            'GROUP_ID' => ['2' => 'R'],
        ]);

        if (!$iblockIdNaturalUser) {
            throw new MigrationException('Ошибка при добавлении инфоблока ' . $ib->LAST_ERROR);
        }

        $propsNaturalUser = [
            [
                "NAME" => "Заголовок (рус)",
                "SORT" => "100",
                "CODE" => "TITLE_RU",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockIdNaturalUser,
            ],
            [
                "NAME" => "Заголовок (анг)",
                "SORT" => "100",
                "CODE" => "TITLE_EN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockIdNaturalUser,
            ],
            [
                "NAME" => "Заголовок (кит)",
                "SORT" => "100",
                "CODE" => "TITLE_CN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockIdNaturalUser,
            ],
            [
                "NAME" => "Превью текст (рус)",
                "SORT" => "100",
                "CODE" => "PREVIEW_TEXT_RU",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockIdNaturalUser,
            ],
            [
                "NAME" => "Превью текст (англ)",
                "SORT" => "100",
                "CODE" => "PREVIEW_TEXT_EN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockIdNaturalUser,
            ],
            [
                "NAME" => "Превью текст (кит)",
                "SORT" => "100",
                "CODE" => "PREVIEW_TEXT_CN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockIdNaturalUser,
            ],
            [
                "NAME" => "Изображение для баннера (480w)",
                "CODE" => "IMAGE_480",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockIdNaturalUser,
            ],
            [
                "NAME" => "Изображение для баннера (768w)",
                "CODE" => "IMAGE_768",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockIdNaturalUser,
            ],
            [
                "NAME" => "Изображение для баннера (1024w)",
                "CODE" => "IMAGE_1024",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockIdNaturalUser,
            ],
            [
                "NAME" => "Изображение для баннера (1440w)",
                "CODE" => "IMAGE_1440",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockIdNaturalUser,
            ],
            [
                "NAME" => "Ссылка на источник баннера",
                "SORT" => "100",
                "CODE" => "LINK",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockIdNaturalUser,
            ]
        ];

        foreach ($propsNaturalUser as $prop) {
            $this->addIblockElementProperty($prop);
        }

        PhysicBanner::create([
            'NAME' => 'Главная - создай свое главное украшение',
            'CODE' => 'index_jewerlly',
            'ACTIVE' => 'N'
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
        //
    }
}

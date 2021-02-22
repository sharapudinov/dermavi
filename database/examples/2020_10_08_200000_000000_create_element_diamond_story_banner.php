<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;

final class CreateElementDiamondStoryBanner20201008200000000000 extends BitrixMigration
{
    private const IBLOCK_CODE = 'diamond_story_banner';

    private const IBLOCK_TYPE = 'banners';

    private const ELEMENT_CODE = 'default_banner';

    /**
     * Run the migration.
     * @throws Exception
     */
    public function up()
    {
        $this->createElement();
    }

    /**
     * Reverse the migration.
     */
    public function down()
    {
        // Оставляем элемент
    }

    /**
     * @return int
     * @throws \Bitrix\Main\LoaderException
     */
    private function createElement(): int
    {
        Loader::includeModule('iblock');

        $iblockId = 0;
        try {
            $iblockId = $this->getIblockIdByCode(static::IBLOCK_CODE, static::IBLOCK_TYPE);
        } catch (Exception $exception) {
            // it's ok
        }

        if (!$iblockId) {
            return 0;
        }

        $element = CIBlockElement::GetList([], ['IBLOCK_ID' => $iblockId, '=CODE' => static::ELEMENT_CODE], false, false, ['ID'])
            ->Fetch();

        if ($element) {
            return (int)$element['ID'];
        }

        $elementId = (new CIBlockElement())->Add(
            [
                'IBLOCK_ID' => $iblockId,
                'ACTIVE' => 'Y',
                'NAME' => 'Баннер вверху страницы',
                'CODE' => static::ELEMENT_CODE,
                'PROPERTY_VALUES' => [
                    'IMAGE_MAIN_EN' => $this->getImageFields('slide_3_desktop.png'),
                    'IMAGE_ADAPTIVE_EN' => $this->getImageFields('slide_3_mobile.png'),
                    'TITLE_RU' => 'Гарантия подлинности',
                    'SUBTITLE_RU' => 'для каждого бриллианта',
                    'TITLE_EN' => 'Guarantee of origin',
                    'SUBTITLE_EN' => 'for every polished diamond',
                    'TITLE_CN' => '原产地保证 每个抛光钻石',
                    'SUBTITLE_CN' => '',
                ],
            ]
        );

        return (int)$elementId;
    }

    /**
     * @param string $fileName
     * @return array|bool|null
     */
    private function getImageFields(string $fileName)
    {
        return CFile::MakeFileArray(
            __DIR__ . '/files/2020_10_08_200000_000000_create_element_diamond_story_banner/' . $fileName
        );
    }
}

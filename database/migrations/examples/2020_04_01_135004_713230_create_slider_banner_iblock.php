<?php

use App\Helpers\LanguageHelper;
use App\Models\Banners\SliderBanner;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для создания ИБ "Баннеры для слайдера"
 * Class CreateSliderBannerIblock20200401135004713230
 */
class CreateSliderBannerIblock20200401135004713230 extends BitrixMigration
{
    /** @var array|array[] $properties Массив свойств */
    private $properties = [
        [
            'CODE' => 'IMAGE_MAIN',
            'NAME' => 'Изображение (основное)',
            'PROPERTY_TYPE' => 'F',
            'IS_REQUIRED' => 'Y'
        ],
        [
            'CODE' => 'IMAGE_ADAPTIVE',
            'NAME' => 'Изображение (адаптив)',
            'PROPERTY_TYPE' => 'F',
        ],
        [
            'CODE' => 'TYPE',
            'NAME' => 'Тип баннера',
            'IS_REQUIRED' => 'Y',
            'PROPERTY_TYPE' => 'L',
            'VALUES' => [
                ['VALUE' => 'Кнопки по центру', 'DEF' => 'Y'],
                ['VALUE' => 'Кнопки справа', 'DEF' => 'N'],
                ['VALUE' => 'Ссылка', 'DEF' => 'N']
            ]
        ],
        [
            'CODE' => 'LINK',
            'NAME' => 'Ссылка (если тип "ссылка")'
        ],
        [
            'CODE' => 'TITLE_RU',
            'NAME' => 'Заголовок (рус)'
        ],
        [
            'CODE' => 'TITLE_EN',
            'NAME' => 'Заголовок (англ)'
        ],
        [
            'CODE' => 'TITLE_CN',
            'NAME' => 'Заголовок (кит)'
        ],
        [
            'CODE' => 'SUBTITLE_RU',
            'NAME' => 'Подзаголовок (рус)'
        ],
        [
            'CODE' => 'SUBTITLE_EN',
            'NAME' => 'Подзаголовок (англ)'
        ],
        [
            'CODE' => 'SUBTITLE_CN',
            'NAME' => 'Подзаголовок (кит)'
        ],
        [
            'CODE' => 'BUTTONS_RU',
            'NAME' => 'Кнопки (рус)',
            'WITH_DESCRIPTION' => 'Y',
            'MULTIPLE' => 'Y'
        ],
        [
            'CODE' => 'BUTTONS_EN',
            'NAME' => 'Кнопки (англ)',
            'WITH_DESCRIPTION' => 'Y',
            'MULTIPLE' => 'Y'
        ],
        [
            'CODE' => 'BUTTONS_CN',
            'NAME' => 'Кнопки (кит)',
            'WITH_DESCRIPTION' => 'Y',
            'MULTIPLE' => 'Y'
        ]
    ];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlock())->Add([
            'ACTIVE' => 'Y',
            'NAME' => 'Баннеры для слайдера',
            'CODE' => SliderBanner::IBLOCK_CODE,
            'IBLOCK_TYPE_ID' => 'banners',
            'SITE_ID' => array_keys(LanguageHelper::getAvailableLanguages()),
            'GROUP_ID' => ['2' => 'R', '1' => 'X']
        ]);

        foreach ($this->properties as $key => $property) {
            $property['IBLOCK_ID'] = SliderBanner::iblockId();
            $property['SORT'] = 500 + ($key + 1);

            (new CIBlockProperty())->Add($property);
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
        CIBlock::Delete(SliderBanner::iblockId());
    }
}

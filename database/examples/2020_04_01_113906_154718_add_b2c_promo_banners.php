<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use App\Models\Main\PromoBanner;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий миграцию для создания новых промо-баннеров в бд
 * Class AddB2cPromoBanners20200401113906154718
 */
class AddB2cPromoBanners20200401113906154718 extends BitrixMigration
{
    private const DIAMONDS_REPUTATION = 'diamonds-reputation';

    private const SOCIAL_ACTIVITIES = 'social-activities-b2c';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        PromoBanner::first()->update(['CODE' => 'social-activities']);

        (new CIBlockElement())->Add([
            'NAME' => 'Бриллианты с кристально чистой репутацией',
            'CODE' => self::DIAMONDS_REPUTATION,
            'IBLOCK_ID' => PromoBanner::iblockId(),
            'PROPERTY_VALUES' => [
                'TITLE_RU' => 'Бриллианты с&nbsp;кристально чистой репутацией',
                'TITLE_EN' => 'Бриллианты с&nbsp;кристально чистой репутацией',
                'TITLE_CN' => 'Бриллианты с&nbsp;кристально чистой репутацией',
                'DESCRIPTION_RU' => 'АЛРОСА&nbsp;&mdash; единственная в&nbsp;мире компания, которая производит из&nbsp;добытых алмазов бриллианты и&nbsp;ювелирные украшения с&nbsp;ними.',
                'DESCRIPTION_EN' => 'АЛРОСА&nbsp;&mdash; единственная в&nbsp;мире компания, которая производит из&nbsp;добытых алмазов бриллианты и&nbsp;ювелирные украшения с&nbsp;ними.',
                'DESCRIPTION_CN' => 'АЛРОСА&nbsp;&mdash; единственная в&nbsp;мире компания, которая производит из&nbsp;добытых алмазов бриллианты и&nbsp;ювелирные украшения с&nbsp;ними.',
                'INFOGRAPHICS_TEXTS_RU' => [
                    'каждый <span class="mobile-carousel__item-digit">4</span>-й алмаз',
                    '<span class="mobile-carousel__item-digit">100%</span>'
                ],
                'INFOGRAPHICS_TEXTS_EN' => [
                    'каждый <span class="mobile-carousel__item-digit">4</span>-й алмаз',
                    '<span class="mobile-carousel__item-digit">100%</span>'
                ],
                'INFOGRAPHICS_TEXTS_CN' => [
                    'каждый <span class="mobile-carousel__item-digit">4</span>-й алмаз',
                    '<span class="mobile-carousel__item-digit">100%</span>'
                ],
                'BUTTON_NAME_RU' => 'Подробнее об алроса',
                'BUTTON_NAME_EN' => 'Подробнее об алроса',
                'BUTTON_NAME_CN' => 'Подробнее об алроса',
                'BUTTON_LINK' => '/about/'
            ]
        ]);

        $banner = PromoBanner::filter(['CODE' => self::DIAMONDS_REPUTATION])->getlist()->first();
        $banner->update([
            'PROPERTY_INFOGRAPHICS_TEXTS_RU_DESCRIPTION' => [
                'в&nbsp;мире добыт на&nbsp;наших месторождениях',
                'гарантия подлинности в&nbsp;каждом украшении'
            ],
            'PROPERTY_INFOGRAPHICS_TEXTS_EN_DESCRIPTION' => [
                'в&nbsp;мире добыт на&nbsp;наших месторождениях',
                'гарантия подлинности в&nbsp;каждом украшении'
            ],
            'PROPERTY_INFOGRAPHICS_TEXTS_CN_DESCRIPTION' => [
                'в&nbsp;мире добыт на&nbsp;наших месторождениях',
                'гарантия подлинности в&nbsp;каждом украшении'
            ]
        ]);

        (new CIBlockElement())->Add([
            'NAME' => 'Бриллианты АЛРОСА помогают людям',
            'CODE' => self::SOCIAL_ACTIVITIES,
            'IBLOCK_ID' => PromoBanner::iblockId(),
            'PROPERTY_VALUES' => [
                'TITLE_RU' => 'Бриллианты АЛРОСА помогают людям',
                'TITLE_EN' => 'Бриллианты АЛРОСА помогают людям',
                'TITLE_CN' => 'Бриллианты АЛРОСА помогают людям',
                'INFOGRAPHICS_TEXTS_RU' => [
                    '№1',
                    '10 млрд <span class="rub">б</span>',
                    'Более 500'
                ],
                'INFOGRAPHICS_TEXTS_EN' => [
                    '№1',
                    '10 млрд <span class="rub">б</span>',
                    'Более 500'
                ],
                'INFOGRAPHICS_TEXTS_CN' => [
                    '№1',
                    '10 млрд <span class="rub">б</span>',
                    'Более 500'
                ],
                'BUTTON_NAME_RU' => 'Узнать больше',
                'BUTTON_NAME_EN' => 'Узнать больше',
                'BUTTON_NAME_CN' => 'Узнать больше',
                'BUTTON_LINK' => '/about/social-responsibility/'
            ]
        ]);

        $banner = PromoBanner::filter(['CODE' => self::SOCIAL_ACTIVITIES])->getlist()->first();
        $banner->update([
            'PROPERTY_INFOGRAPHICS_TEXTS_RU_DESCRIPTION' => [
                'в&nbsp;рейтинге социальной ответственности среди компаний отрасли',
                'Порядка 10 млрд <span class="rub">б</span> социальных инвестиций в&nbsp;год',
                'проектов и инициатив ежегодно'
            ],
            'PROPERTY_INFOGRAPHICS_TEXTS_EN_DESCRIPTION' => [
                'в&nbsp;рейтинге социальной ответственности среди компаний отрасли',
                'Порядка 10 млрд <span class="rub">б</span> социальных инвестиций в&nbsp;год',
                'проектов и инициатив ежегодно'
            ],
            'PROPERTY_INFOGRAPHICS_TEXTS_CN_DESCRIPTION' => [
                'в&nbsp;рейтинге социальной ответственности среди компаний отрасли',
                'Порядка 10 млрд <span class="rub">б</span> социальных инвестиций в&nbsp;год',
                'проектов и инициатив ежегодно'
            ]
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
        /** @var Collection|PromoBanner[] $banners */
        $banners = PromoBanner::filter(['CODE' => [self::SOCIAL_ACTIVITIES, self::DIAMONDS_REPUTATION]])->getList();
        foreach ($banners as $banner) {
            $banner->delete();
        }
    }
}

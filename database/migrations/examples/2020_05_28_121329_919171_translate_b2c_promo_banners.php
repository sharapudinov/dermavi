<?php

use App\Models\Main\PromoBanner;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для записи переводов для баннеров b2c
 * Class TranslateB2cPromoBanners20200528121329919171
 */
class TranslateB2cPromoBanners20200528121329919171 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $banner = PromoBanner::filter(['CODE' => 'diamonds-reputation'])->getList()->first();
        $banner->update([
            'PROPERTY_TITLE_EN_VALUE' => 'DIAMONDS WITH A CRYSTAL-CLEAR REPUTATION',
            'PROPERTY_TITLE_CN_VALUE' => '我们的钻石晶莹剔透',
            'PROPERTY_DESCRIPTION_EN_VALUE' => 'ALROSA is the only company in the world that produces gem-cut diamonds and diamond jewelry from its own rough diamonds.',
            'PROPERTY_DESCRIPTION_CN_VALUE' => '阿尔罗萨是世界上唯一一家用开采的金刚石生产钻石和制作珠宝饰品的公司。',
            'PROPERTY_INFOGRAPHICS_TEXTS_EN_VALUE' => [
                'ONE IN EVERY <span class="mobile-carousel__item-digit">4</span> DIAMONDS',
                'Each piece'
            ],
            'PROPERTY_INFOGRAPHICS_TEXTS_EN_DESCRIPTION' => [
                'in the world comes from our diamond mines',
                'of jewelry comes with a 100% guarantee of authenticity'
            ],
            'PROPERTY_INFOGRAPHICS_TEXTS_CN_VALUE' => [
                '世界上每四颗金刚石中就有',
                '我们提供'
            ],
            'PROPERTY_INFOGRAPHICS_TEXTS_CN_DESCRIPTION' => [
                '一颗是在我们的矿床开采的。',
                '的每种珠宝饰品100%保真。'
            ],
            'PROPERTY_BUTTON_NAME_EN_VALUE' => 'More about ALROSA',
            'PROPERTY_BUTTON_NAME_CN_VALUE' => '了解更多关于公司的信息'
        ]);

        $banner = PromoBanner::filter(['CODE' => 'social-activities-b2c'])->getList()->first();
        $banner->update([
            'PROPERTY_TITLE_EN_VALUE' => 'ALROSA DIAMONDS HELP PEOPLE',
            'PROPERTY_TITLE_CN_VALUE' => '阿尔罗萨钻石肩负社会责任',
            'PROPERTY_INFOGRAPHICS_TEXTS_EN_VALUE' => [
                'No. 1',
                '10 BILLION RUBLES',
                'MORE THAN 500'
            ],
            'PROPERTY_INFOGRAPHICS_TEXTS_EN_DESCRIPTION' => [
                'in the industry\'s corporate social responsibility rating',
                'At least 10 billion rubles\' worth of social investments every year',
                'annual projects and initiatives'
            ],
            'PROPERTY_INFOGRAPHICS_TEXTS_CN_VALUE' => [
                '阿尔罗萨',
                '一百亿卢布',
                '500+'
            ],
            'PROPERTY_INFOGRAPHICS_TEXTS_CN_DESCRIPTION' => [
                '在行业公司社会责任排行榜中名列第一',
                '我们公司每年社会投资额约为一百亿卢布',
                '每年我们公司提出500多个项目和建议'
            ],
            'PROPERTY_BUTTON_NAME_EN_VALUE' => 'Learn more',
            'PROPERTY_BUTTON_NAME_CN_VALUE' => '了解更多'
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

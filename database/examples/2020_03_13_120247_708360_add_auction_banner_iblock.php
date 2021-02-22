<?php

use App\Models\Banners\AuctionBanner;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для создания инфоблока "Баннер аукционов на главной"
 * Class AddAuctionBannerIblock20200313120247708360
 */
class AddAuctionBannerIblock20200313120247708360 extends BitrixMigration
{
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
            'NAME' => 'Баннер аукционов на главной',
            'CODE' => AuctionBanner::IBLOCK_CODE,
            'IBLOCK_TYPE_ID' => 'banners',
            'SITE_ID' => ['s1', 's2', 's3'],
            'GROUP_ID' => ['1' => 'X', '2' => 'R']
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '100',
            'IS_REQUIRED' => 'Y',
            'NAME' => 'Заголовок (англ)',
            'CODE' => 'TITLE_EN',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '101',
            'NAME' => 'Заголовок (рус)',
            'CODE' => 'TITLE_RU',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '102',
            'NAME' => 'Заголовок (кит)',
            'CODE' => 'TITLE_CN',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '103',
            'IS_REQUIRED' => 'Y',
            'NAME' => 'Описание (англ)',
            'CODE' => 'DESCRIPTION_EN',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '104',
            'NAME' => 'Описание (рус)',
            'CODE' => 'DESCRIPTION_RU',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '105',
            'NAME' => 'Описание (кит)',
            'CODE' => 'DESCRIPTION_CN',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '106',
            'IS_REQUIRED' => 'Y',
            'NAME' => 'Текст над датой (англ)',
            'CODE' => 'UPPER_DATE_TEXT_EN',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '107',
            'NAME' => 'Текст над датой (рус)',
            'CODE' => 'UPPER_DATE_TEXT_RU',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '108',
            'NAME' => 'Текст над датой (кит)',
            'CODE' => 'UPPER_DATE_TEXT_CN',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '109',
            'IS_REQUIRED' => 'Y',
            'NAME' => 'Даты проведения (англ)',
            'CODE' => 'DATE_EN',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '110',
            'NAME' => 'Даты проведения (рус)',
            'CODE' => 'DATE_RU',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '111',
            'NAME' => 'Даты проведения (кит)',
            'CODE' => 'DATE_CN',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '112',
            'IS_REQUIRED' => 'Y',
            'NAME' => 'Ссылка',
            'CODE' => 'LINK',
            'IBLOCK_ID' => AuctionBanner::iblockId()
        ]);

        AuctionBanner::create([
            'NAME' => 'Баннер',
            'PROPERTY_TITLE_EN_VALUE' => 'ALROSA Polished Diamonds Auction',
            'PROPERTY_TITLE_RU_VALUE' => 'Аукционы редких бриллиантов',
            'PROPERTY_TITLE_CN_VALUE' => '阿尔罗萨抛光钻石拍卖',
            'PROPERTY_DESCRIPTION_EN_VALUE' => 'The ALROSA auction will give you access to unique natural diamonds with certified proof of origin.',
            'PROPERTY_DESCRIPTION_RU_VALUE' => 'Примите участие в аукционе АЛРОСА и получите доступ к уникальным природным бриллиантам с гарантией происхождения.',
            'PROPERTY_DESCRIPTION_CN_VALUE' => '欢迎大家参加阿尔罗萨拍卖会，抓住机遇购买具有天然来源保证的独特钻石。',
            'PROPERTY_UPPER_DATE_TEXT_EN_VALUE' => 'Event\'s dates',
            'PROPERTY_UPPER_DATE_TEXT_RU_VALUE' => 'Даты проведения',
            'PROPERTY_UPPER_DATE_TEXT_CN_VALUE' => '活動日期',
            'PROPERTY_DATE_EN_VALUE' => '27.02-11.03',
            'PROPERTY_DATE_RU_VALUE' => '27.02-11.03',
            'PROPERTY_DATE_CN_VALUE' => '27.02-11.03',
            'PROPERTY_LINK_VALUE' => '/auctions/'
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
        CIBlock::Delete(AuctionBanner::iblockId());
    }
}

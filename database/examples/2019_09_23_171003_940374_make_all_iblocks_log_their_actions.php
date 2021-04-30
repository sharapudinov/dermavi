<?php

use App\Models\About\DiamondStoryBlock;
use App\Models\About\DiamondStoryVideo;
use App\Models\About\EnvironmentalResponsibility;
use App\Models\About\KimberleyProcess;
use App\Models\About\Office;
use App\Models\About\Partner;
use App\Models\About\RussianCut;
use App\Models\About\SocialResponsibility;
use App\Models\Auctions\Auction;
use App\Models\Auctions\AuctionLot;
use App\Models\Auctions\AuctionRule;
use App\Models\Auctions\AuctionRuleFile;
use App\Models\Blog\Article;
use App\Models\Blog\Category;
use App\Models\Blog\GridElement;
use App\Models\Catalog\Catalog;
use App\Models\Catalog\PaidService;
use App\Models\Client\PersonalSectionDocumentKind;
use App\Models\ForPartners\FAQ;
use App\Models\ForPartners\PartnerDocument;
use App\Models\Main\PromoBanner;
use App\Models\Main\UrlHashTags;
use App\Models\Sale\DeliveryTime;
use App\Models\Sale\PickupPoint;
use App\Models\Sale\PickupTime;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для включения логирования всех действий всех инфоблоков
 * Class MakeAllIblocksLogTheirActions20190923171003940374
 */
class MakeAllIblocksLogTheirActions20190923171003940374 extends BitrixMigration
{
    /** @var array|int[] $iblocksIds - Массив идентификаторов инфоблоков */
    private $iblocksIds = [];

    /**
     * MakeAllIblocksLogTheirActions20190923171003940374 constructor.
     */
    public function __construct()
    {
        $this->iblocksIds = [
            PromoBanner::iblockId(),
            UrlHashTags::iblockId(),
            DiamondStoryBlock::iblockId(),
            DiamondStoryVideo::iblockId(),
            KimberleyProcess::iblockId(),
            Partner::iblockId(),
            Office::iblockId(),
            RussianCut::iblockId(),
            SocialResponsibility::iblockId(),
            EnvironmentalResponsibility::iblockId(),
            PersonalSectionDocumentKind::iblockId(),
            Diamond::iblockID(),
            PaidService::iblockId(),
            Article::iblockID(),
            Category::iblockID(),
            GridElement::iblockID(),
            Auction::iblockId(),
            AuctionLot::iblockId(),
            AuctionRule::iblockId(),
            AuctionRuleFile::iblockId(),
            DeliveryTime::iblockId(),
            PickupTime::iblockId(),
            PickupPoint::iblockId(),
            FAQ::iblockId(),
            PartnerDocument::iblockId(),
            App\Models\ForCustomers\FAQ::iblockId(),
            App\Models\ForCustomers\Info::iblockId(),
            App\Models\ForCustomers\Slider::iblockId()
        ];

        parent::__construct();
    }

    /**
     * Обновляет поля инфоблока (поля для логирования)
     *
     * @param string $require - Включить|Выключить ('Y'|'N')
     */
    private function setFields(string $require): void
    {
        foreach ($this->iblocksIds as $iblockId) {
            $fields = CIBlock::getFields($iblockId);
            $fields['LOG_SECTION_ADD']['IS_REQUIRED'] = $require;
            $fields['LOG_SECTION_EDIT']['IS_REQUIRED'] = $require;
            $fields['LOG_SECTION_DELETE']['IS_REQUIRED'] = $require;
            $fields['LOG_ELEMENT_ADD']['IS_REQUIRED'] = $require;
            $fields['LOG_ELEMENT_EDIT']['IS_REQUIRED'] = $require;
            $fields['LOG_ELEMENT_DELETE']['IS_REQUIRED'] = $require;
        }
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->setFields('Y');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->setFields('N');
    }
}

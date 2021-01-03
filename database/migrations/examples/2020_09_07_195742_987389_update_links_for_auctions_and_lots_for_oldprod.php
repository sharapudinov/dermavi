<?php

use App\Models\Auctions\Auction;
use App\Models\Auctions\AuctionLot;
use App\Models\Auctions\AuctionPB;
use App\Models\Auctions\AuctionPBLot;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class UpdateLinksForAuctionsAndLotsForOldprod20200907195742987389 extends BitrixMigration
{

    public const SEARCH = 'alrosadiamond.ru';
    public const REPLACE = 'diamonds.alrosa.ru';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->updateLinksAuctions();
        $this->updateLots();
        $this->updateAuctionsPB();
        $this->updateLotsPB();
    }

    /**
     * Обновление ссылок у аукционов
     */
    public function updateLinksAuctions(): void
    {
        $auctions = Auction::filter([])->getList();
        foreach ($auctions as $auction) {
            // обновление элементов
            $auction->update([
                'PROPERTY_NOTIFY_AND_REQUEST_VIEWING_EXCEL_EXPORT_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auction['PROPERTY_NOTIFY_AND_REQUEST_VIEWING_EXCEL_EXPORT_VALUE']),
                'PROPERTY_EXCEL_EXPORT_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auction['PROPERTY_EXCEL_EXPORT_VALUE']),
                'PROPERTY_AUCTION_NOTIFICATION_MAIL_PREVIEW_LINK_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auction['PROPERTY_AUCTION_NOTIFICATION_MAIL_PREVIEW_LINK_VALUE']),
                'PROPERTY_AUCTION_PREVIEW_LINK_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auction['PROPERTY_AUCTION_PREVIEW_LINK_VALUE']),
                'PROPERTY_VIEWING_TIME_SLOTS_INTERFACE_LINK_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auction['PROPERTY_VIEWING_TIME_SLOTS_INTERFACE_LINK_VALUE']),
                'PROPERTY_USERS_TO_NOTIFICATE_INTERFACE_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auction['PROPERTY_USERS_TO_NOTIFICATE_INTERFACE_VALUE'])
            ]);
        }
    }

    /**
     * Обновление ссылок у лотов обычных аукционов
     */
    public function updateLots(): void
    {
        $auctionLots = AuctionLot::filter([])->getList();
        foreach ($auctionLots as $auctionLot) {
            $auctionLot->update([
                'PROPERTY_ATTACHED_DIAMONDS_CHARACTERISTICS_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auctionLot['PROPERTY_ATTACHED_DIAMONDS_CHARACTERISTICS_VALUE']),
                'PROPERTY_DIAMONDS_ATTACHING_INTERFACE_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auctionLot['PROPERTY_DIAMONDS_ATTACHING_INTERFACE_VALUE'])
            ]);
        }
    }

    /**
     * Обновление ссылок у аукционов PB
     */
    public function updateAuctionsPB(): void
    {
        $auctionsPB = AuctionPB::filter([])->getList();
        foreach ($auctionsPB as $auctionPB) {
            // обновление элементов
            $auctionPB->update([
                'PROPERTY_NOTIFY_AND_REQUEST_VIEWING_EXCEL_EXPORT_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auctionPB['PROPERTY_NOTIFY_AND_REQUEST_VIEWING_EXCEL_EXPORT_VALUE']),
                'PROPERTY_EXCEL_EXPORT_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auctionPB['PROPERTY_EXCEL_EXPORT_VALUE']),
                'PROPERTY_AUCTION_NOTIFICATION_MAIL_PREVIEW_LINK_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auctionPB['PROPERTY_AUCTION_NOTIFICATION_MAIL_PREVIEW_LINK_VALUE']),
                'PROPERTY_AUCTION_PREVIEW_LINK_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auctionPB['PROPERTY_AUCTION_PREVIEW_LINK_VALUE']),
                'PROPERTY_VIEWING_TIME_SLOTS_INTERFACE_LINK_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auctionPB['PROPERTY_VIEWING_TIME_SLOTS_INTERFACE_LINK_VALUE']),
                'PROPERTY_USERS_TO_NOTIFICATE_INTERFACE_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auctionPB['PROPERTY_USERS_TO_NOTIFICATE_INTERFACE_VALUE'])
            ]);
        }
    }

    /**
     * Обновление ссылок у лотов аукционов PB
     */
    public function updateLotsPB(): void
    {
        $auctionLotsPB = AuctionPBLot::filter([])->getList();
        foreach ($auctionLotsPB as $auctionLotPB) {
            $auctionLotPB->update([
                'PROPERTY_ATTACHED_DIAMONDS_CHARACTERISTICS_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auctionLotPB['PROPERTY_ATTACHED_DIAMONDS_CHARACTERISTICS_VALUE']),
                'PROPERTY_DIAMONDS_ATTACHING_INTERFACE_VALUE' => str_replace(self::SEARCH, self::REPLACE,
                    $auctionLotPB['PROPERTY_DIAMONDS_ATTACHING_INTERFACE_VALUE'])
            ]);
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
        //
    }
}

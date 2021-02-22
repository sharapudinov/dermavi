<?php

use App\Models\Auctions\Auction;
use App\Models\Auctions\AuctionLot;
use App\Models\Auctions\AuctionPB;
use App\Models\Auctions\AuctionPBLot;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class AddPropertyLogToAuctionAndLot20200923114725882397 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        (new CIBlockProperty)->Add(
            [
                'NAME'          => 'Логи аукциона',
                'CODE'          => 'AUCTION_SHOW_LOG',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE'     => 'AuctionLogs',
                'SORT'          => '5000',
                'IBLOCK_ID'     => AuctionPB::iblockId(),
            ]
        );
        (new CIBlockProperty)->Add(
            [
                'NAME'          => 'Логи аукциона',
                'CODE'          => 'AUCTION_SHOW_LOG',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE'     => 'AuctionLogs',
                'SORT'          => '5000',
                'IBLOCK_ID'     => Auction::iblockId(),
            ]
        );
        (new CIBlockProperty)->Add(
            [
                'NAME'          => 'Логи аукциона',
                'CODE'          => 'AUCTION_SHOW_LOG',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE'     => 'AuctionLogs',
                'SORT'          => '5000',
                'IBLOCK_ID'     => AuctionLot::iblockId(),
            ]
        );
        (new CIBlockProperty)->Add(
            [
                'NAME'          => 'Логи аукциона',
                'CODE'          => 'AUCTION_SHOW_LOG',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE'     => 'AuctionLogs',
                'SORT'          => '5000',
                'IBLOCK_ID'     => AuctionPBLot::iblockId(),
            ]
        );
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {
        //
    }
}

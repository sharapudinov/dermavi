<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use \App\Models\Auctions\Auction;
use \App\Models\Auctions\AuctionPB;

class AuctionsAddInfoFields20200917153029796090 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $auctionIblockId = $this->getIblockIdByCode(Auction::IBLOCK_CODE);

        $this->addIblockElementProperty(
            [
                'NAME'          => 'Информация о просмотре (рус)',
                'CODE'          => 'VIEWING_INFO_RU',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE'     => 'HTML',
                'SORT'          => '512',
                'IBLOCK_ID'     => $auctionIblockId,
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME'          => 'Информация о просмотре (англ)',
                'CODE'          => 'VIEWING_INFO_EN',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE'     => 'HTML',
                'SORT'          => '512',
                'IBLOCK_ID'     => $auctionIblockId,
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME'          => 'Информация о просмотре (кит)',
                'CODE'          => 'VIEWING_INFO_CN',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE'     => 'HTML',
                'SORT'          => '512',
                'IBLOCK_ID'     => $auctionIblockId,
            ]
        );

        $auctionPbIblockId = $this->getIblockIdByCode(AuctionPB::IBLOCK_CODE);

        $this->addIblockElementProperty(
            [
                'NAME'          => 'Информация о просмотре (рус)',
                'CODE'          => 'VIEWING_INFO_RU',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE'     => 'HTML',
                'SORT'          => '512',
                'IBLOCK_ID'     => $auctionPbIblockId,
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME'          => 'Информация о просмотре (англ)',
                'CODE'          => 'VIEWING_INFO_EN',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE'     => 'HTML',
                'SORT'          => '512',
                'IBLOCK_ID'     => $auctionPbIblockId,
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME'          => 'Информация о просмотре (кит)',
                'CODE'          => 'VIEWING_INFO_CN',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE'     => 'HTML',
                'SORT'          => '512',
                'IBLOCK_ID'     => $auctionPbIblockId,
            ]
        );

        return true;
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $auctionIblockId = $this->getIblockIdByCode(Auction::IBLOCK_CODE);

        $this->deleteIblockElementPropertyByCode($auctionIblockId, 'VIEWING_INFO_RU');
        $this->deleteIblockElementPropertyByCode($auctionIblockId, 'VIEWING_INFO_EN');
        $this->deleteIblockElementPropertyByCode($auctionIblockId, 'VIEWING_INFO_CN');

        $auctionPbIblockId = $this->getIblockIdByCode(AuctionPB::IBLOCK_CODE);

        $this->deleteIblockElementPropertyByCode($auctionPbIblockId, 'VIEWING_INFO_RU');
        $this->deleteIblockElementPropertyByCode($auctionPbIblockId, 'VIEWING_INFO_EN');
        $this->deleteIblockElementPropertyByCode($auctionPbIblockId, 'VIEWING_INFO_CN');

        return true;
    }
}

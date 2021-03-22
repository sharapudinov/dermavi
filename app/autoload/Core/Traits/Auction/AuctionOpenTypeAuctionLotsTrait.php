<?php

namespace App\Core\Traits\Auction;

use App\Models\Auctions\Auction;
use App\Models\Auctions\AuctionPB;

/**
 * Trait AuctionLotOpenTypeTrait
 * Проверка на наличие лотов Открытого Аукциона
 *
 * @package App\Core\Traits\Auction
 */
trait AuctionOpenTypeAuctionLotsTrait
{
    /**
     * Есть ли лоты Открытого Аукциона
     *
     * @param Auction|AuctionPB $auction - Модель аукциона
     * @return bool
     */
    protected function hasOpenTypeAuctionLots($auction): bool
    {
        foreach ($auction->lots as $lot) {
            if ($lot->isOpenTypeAuction()) {
                return true;
            }
        }

        return false;
    }
}

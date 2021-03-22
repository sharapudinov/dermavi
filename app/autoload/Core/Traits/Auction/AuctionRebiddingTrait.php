<?php

namespace App\Core\Traits\Auction;

use App\Models\Auctions\Auction;
use App\Models\Auctions\AuctionLot;
use App\Models\Auctions\AuctionPB;
use App\Models\Auctions\AuctionPBLot;
use App\Models\Auctions\LotBid;
use App\Models\User;

/**
 * Трейт для работы с переторжками аукциона
 * Trait AuctionRebiddingTrait
 * @package App\Core\Traits\Auction
 */
trait AuctionRebiddingTrait
{
    /**
     * Определяет есть ли в аукционе лоты с переторжками и допущен ли до них пользователь
     *
     * @param Auction|AuctionPB $auction - Модель аукциона
     *
     * @return bool
     */
    protected function hasRebiddingLotsForUser($auction): bool
    {
        /** @var User $user - Модель текущего пользователя */
        $user = user();
        if (!$user) {
            return false;
        }

        foreach ($auction->lots as $lot) {
            if ($this->isRebiddingLotForUser($lot, $user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет есть ли в лоте аукциона переторжка и допущен ли до нее пользователь
     *
     * @param AuctionLot|AuctionPBLot $auctionLot - Модель лота аукциона
     * @param User $user - Модель пользователя
     *
     * @return bool
     */
    protected function isRebiddingLotForUser($auctionLot, User $user): bool
    {
        if ($auctionLot->isRebidding()) {
            foreach ($auctionLot->bids as $bid) {
                if ((($bid->getUserId() == $user->getId())
                    || $user->isAdmin()
                    || $user->isAuctionManager())
                    && $auctionLot->isRebidding()
                    && $bid->getBid() == $auctionLot->getRebiddingBid()) {
                    return true;
                }
            }
        }

        return false;
    }
}

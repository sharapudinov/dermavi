<?php


namespace App\Core\Traits\Auction;

use App\Models\Auctions\BaseAuction as AuctionModel;
use App\Models\User as UserModel;

/**
 * Трейт для определения уровня доступа пользователя к аукциону
 * Trait AuctionLotTrait
 * @package App\Core\Traits\Auction
 */
trait AuctionAccessControl
{
    /**
     * Определяет открыт ли аукцион для пользователя
     *
     * @param AuctionModel $auction - Модель аукциона
     * @param UserModel $user - Модель пользователя
     *
     * @return bool
     */
    public static function isOpenedForUser(AuctionModel $auction, UserModel $user): bool
    {
        $user_emails = array_map(
            function (UserModel $item) {
                return $item->getEmail();
            },
            $auction->users->all()
        );
        return in_array(
            $user->getEmail(),
            array_merge(
                $auction->getUsersEmailsToNotificate(),
                $user_emails
            )
        );
    }

}

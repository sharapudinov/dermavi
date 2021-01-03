<?php

namespace App\Helpers;

use App\Core\Auctions\Auction as AuctionCore;
use App\Core\Auctions\AuctionLot as AuctionLotCore;
use App\Models\Auctions\Auction;
use App\Models\Auctions\Auction as AuctionModel;
use App\Models\Auctions\AuctionLot;
use App\Models\Auctions\AuctionPB;
use App\Models\Auctions\AuctionPBLot;
use App\Models\Auctions\LotBid;
use App\Models\Auctions\LotBidStatus;
use App\Models\Auctions\PBLotBid;
use App\Models\User;
use Bitrix\Main\Mail\Event;
use Illuminate\Support\Collection;

class EmailTestHelper
{
    protected $events = [
        //        'VIEWING_REQUEST_PB',
        //        'VIEWING_REQUEST',
        'NOTIFY_USERS_ABOUT_NEW_AUCTION',
        'AUCTION_USER_SIGNUP',
        'SIGN_UP_USER',
        'AUCTION_USER_OVERBID',
        'AUCTION_USER_BID_CONFIRM',
        'AUCTION_RESULTS_USER_REBIDDING',
        'AUCTION_RESULTS_USER',
        'AUCTION_RESULTS_MANAGER_SUCCESS',
        'AUCTION_RESULTS_MANAGER_REBIDDING',
        'AUCTION_NEW_BIDS_WHILE_REBIDDING',
        'AUCTION_24_HOURS_LAST',
        'AUCTION_2_HOURS_LAST',
    ];

    /**
     * @return EmailTestHelper
     */
    public static function init()
    {
        return new self();
    }

    /**
     * @return string[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return User::order(['ID' => 'DESC'])->limit(10)->with('country')->getList();
    }

    /**
     * @param bool $isPb
     *
     * @return Collection
     */
    public function getLots(bool $isPb): Collection
    {
        if ($isPb) {
            return AuctionPBLot::filter([])->limit(5)->getList();
        } else {
            return AuctionLot::filter([])->limit(5)->getList();
        }
    }

    /**
     * @param bool $isPb
     *
     * @return Collection
     */
    public function getAuctions(bool $isPb): Collection
    {
        if ($isPb) {
            return AuctionPB::filter([])->limit(5)->getList();
        } else {
            return Auction::filter([])->limit(5)->getList();
        }
    }

    /**
     * @param array|null $request
     *
     * @throws \Bitrix\Main\ArgumentTypeException
     */
    public function send(?array $request): void
    {
        if (null === $request || empty($request['send'])) {
            return;
        }

        $eventName = $request['eventNameManual'] ? $request['eventNameManual'] : $request['eventName'];

        if ($eventName === 'NOTIFY_USERS_ABOUT_NEW_AUCTION') {
            AuctionCore::notifyUsersAboutAuctionStart(
                AuctionModel::filter(
                    ['ID' => $request['auctionIdManual'] ? $request['auctionIdManual'] : $request['auctionId']]
                )->first()
            );

            return;
        }

        /** @var User $user */
        $user = User::filter(
            ['ID' => $request['userIdManual'] ? (int)$request['userIdManual'] : (int)$request['userId']]
        )
                    ->with('country')->first();

        if (!($user instanceof User)) {
            $user = User::filter(
                ['EMAIL' => $request['userIdManual'] ? $request['userIdManual'] : $request['userId']]
            )
                        ->with('country')->first();
        }

        $userLanguageInfo = $user->country->getCountryLanguageInfo();

        $lotId = $request['lotIdManual'] ? $request['lotIdManual'] : $request['lotId'];
        Event::SendImmediate(
            [
                'EVENT_NAME'  => $eventName,
                'LID'         => $userLanguageInfo['site_id'],
                'LANGUAGE_ID' => $userLanguageInfo['language_id'],
                'C_FIELDS'    => [
                    'EMAIL'       => $user->getEmail(),
                    'EMAIL_TO'    => $user->getEmail(),
                    'USER_ID'     => $user->getId(),
                    'LOT_ID'      => $lotId,
                    'AUCTION_ID'  => $request['auctionIdManual'] ? $request['auctionIdManual'] : $request['auctionId'],
                    'YOUR_BID_ID' => $this->getBidId($lotId, $user->getId()),
                    'NEW_BID_ID'  => $this->getBidId($lotId, $user->getId()),
                ],
            ]
        );
    }

    /**
     * @param $lotId
     *
     * @return int|null
     */
    protected function getBidId($lotId, $userId): ?int
    {
        if (null === $lotId) {
            return 0;
        }

        $activeBidStatus = LotBidStatus::filter(['UF_NAME' => AuctionLotCore::BET_STATUS_ACTIVE])->first();

        $bid = LotBid::filter(
            [
                'UF_LOT_ID'        => $lotId,
                'UF_USER_ID'       => $userId,
                'UF_BID_STATUS_ID' => $activeBidStatus->getId(),
            ]
        )->first();

        if (!($bid instanceof LotBid)) {
            $bid = PBLotBid::filter(
                [
                    'UF_LOT_ID'        => $lotId,
                    'UF_USER_ID'       => $userId,
                    'UF_BID_STATUS_ID' => $activeBidStatus->getId(),
                ]
            )->first();
        }

        if (!($bid instanceof PBLotBid)) {
            return LotBid::filter([])->limit(1)->first()->getId();
        }
    }
}

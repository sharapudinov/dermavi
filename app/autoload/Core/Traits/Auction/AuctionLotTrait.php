<?php

namespace App\Core\Traits\Auction;

use App\Api\External\Cutwise\CutwiseWidget;
use App\Core\Browser;
use App\Models\Auctions\AuctionLot;
use App\Models\Auctions\AuctionPBLot;
use App\Models\Catalog\Catalog;
use Illuminate\Support\Collection;

/**
 * Трейт для работы с лотом аукциона
 * Trait AuctionLotTrait
 * @package App\Core\Traits\Auction
 */
trait AuctionLotTrait
{
    /** @var bool $isInternetExplorer - Является ли браузер Internet Explorer'ом */
    private $isInternetExplorer;

    /** @var Collection $perfectMatch - Идеально подходящие товары */
    private $perfectMatch;

    /** @var Collection $recommendedProducts - Рекомендованные товары */
    private $recommendedProducts;

    /** @var AuctionLot $auctionLot - Лот аукциона */
    private $auctionLot;

    /** @var Diamond|null $diamond - Бриллиант, с которым будет происходить работа */
    private $diamond;

    /**
     * Получает и записывет  данные
     *
     * @param Diamond $diamond - Бриллиант
     * @return void
     */
    private function setData(Diamond $diamond): void
    {
        if ($this->auctionLot->diamonds->count() == 1) {
            $diamond->setPerfectMatch();
            foreach ($diamond->getPerfectMatchCollection() as $perfectMatchProduct) {
                $this->perfectMatch->push($perfectMatchProduct);
            }
        }

        $diamond->setRecommendedProducts();
        foreach ($diamond->getRecommendedProductsCollection() as $recommendedProduct) {
            $this->recommendedProducts->push($recommendedProduct);
        }
    }

    /**
     * Устанавливает бриллиант, с которым будет происходить работа
     *
     * @param Diamond $diamond
     * @return self
     */
    protected function workWithDiamond(Diamond $diamond): self
    {
        $this->diamond = $diamond;
        return $this;
    }

    /**
     * Определяет способ получения данных бриллианта
     *
     * @param AuctionLot|AuctionPBLot $auctionLot
     * @return void
     */
    protected function setAuctionLotDiamondsData($auctionLot): void
    {
        /** @var bool $isInternetExplorer - Является ли браузер Internet Explorer'ом */
        $this->isInternetExplorer = (new Browser)->isInternetExplorer();
        $this->perfectMatch = new Collection();
        $this->recommendedProducts = new Collection();
        $this->auctionLot = $auctionLot;

        if ($this->diamond) {
            $this->setData($this->diamond);
        } else {
            foreach ($auctionLot->diamonds as $diamond) {
                $this->setData($diamond);
            }
        }
    }

    /**
     * Проверяет есть ли у пользователя доступ к лоту
     *
     * @param AuctionLot|AuctionPBLot $auctionLot - Лот аукциона
     *
     * @return bool
     */
    protected function isUserHasAccess($auctionLot): bool
    {
        $access = true;
        if ($auctionLot->isRebidding()) {
            $rebiddingUser = false;
            foreach ($auctionLot->bids as $bid) {
                if (($bid->getUserId() == user()->getId()) || user()->isAdmin() || user()->isAuctionManager()) {
                    $rebiddingUser = true;
                }
            }

            if (!$rebiddingUser) {
                $access = false;
            }
        }

        return $access;
    }

    /**
     * Получает идеально подходящие товары
     *
     * @return Collection
     */
    public function getPerfectMatch(): Collection
    {
        return $this->perfectMatch;
    }

    /**
     * Получает рекомендованные товары
     *
     * @return Collection
     */
    public function getRecommendedProducts(): Collection
    {
        return $this->recommendedProducts;
    }
}

<?php

namespace App\Core\Auxiliary\IblockTabs;

use App\Models\Auctions\Auction;
use App\Models\Auctions\AuctionLot;
use App\Models\Auctions\AuctionPB;
use Bitrix\Main\Context;

/**
 * Родительский класс для работы с кастомными табами в инфоблоках
 * Class GeneralTab
 * @package App\Core\Auxiliary\IblockTabs
 */
class GeneralTab
{
    /**
     * Возвращает класс-обработчик конкретного таба
     *
     * @param int $iblockId - Идентификатор инфоблока
     * @return string|null
     */
    private static function getTabClass(int $iblockId): ?string
    {
        /** @var array|string[] $tabClasses - Массив классов-обработчиков табов ['ID_инфоблока' => 'Неймспейс'] */
        $tabClasses = [
            Auction::iblockId() => AuctionTabs::class,
            AuctionPB::iblockId() => AuctionPBTabs::class,
            AuctionLot::iblockId() => AuctionLotTabs::class
        ];

        return $tabClasses[$iblockId];
    }

    /**
     * Возвращает класс-обработчик таба
     *
     * @return array|null
     */
    public static function initTab(): ?array
    {
        $class = self::getTabClass(Context::getCurrent()->getRequest()['IBLOCK_ID']);
        if (!$class) {
            return null;
        }

        $tabset = new $class;
        return [
            'TABSET'  => 'some_tabset_name',
            'GetTabs' => [$tabset, 'getTabList'],
            'ShowTab' => [$tabset, 'showTabContent'],
        ];
    }
}

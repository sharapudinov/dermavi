<?php

namespace App\Core\Auxiliary\IblockTabs;

use App\Models\Auctions\Auction;

/**
 * Класс для работы с табами в инфоблоке аукциона
 * Class AuctionTabs
 *
 * @package App\Core\Auxiliary\IblockTabs
 */
class AuctionTabs implements TabInterface
{
    /**
     * Возвращает описание табов
     *
     * @param array|mixed[] $elementInfo - Массив, описывающий элемент и инфоблок
     * @return array|null
     */
    public function getTabList(array $elementInfo): ?array
    {
        return [
            [
                'DIV' => 'auction_lots_bids_tab',
                'SORT' => 1,
                'TAB' => 'Статус аукциона',
                'TITLE' => 'Статус аукциона',
            ]
        ];
    }

    /**
     * Отображает контент таба
     *
     * @param string $tabCode - Символьный код таба
     * @param array|mixed[] $elementInfo - Массив, описывающий элемент и инфоблок
     * @return void
     */
    public function showTabContent(string $tabCode, array $elementInfo): void
    {
        echo '<a href="' . get_external_url(false, true)
            . '/bitrix/admin/app_auction-status.php?auction_id=' . $elementInfo['ID']
            . '&pb=0" target="_blank">Показать статус аукциона</a>';
    }
}

<?php

namespace App\Core\Auxiliary\IblockTabs;

use App\Core\Auxiliary\IblockTabs\Entity\Tab;
use App\Models\Auctions\AuctionLot;

/**
 * Класс для работы с табом для администратора в инфоблоке
 * Class AdminTab
 * @package App\Core\Auxiliary\IblockTabs
 */
class AuctionLotTabs implements TabInterface
{
    /**
     * Возвращает описание табов
     *
     * @param array|mixed[] $elementInfo - Массив, описывающий элемент и инфоблок
     * @return array|null
     */
    public function getTabList(array $elementInfo): ?array
    {
        return user()->isAdmin() ? [
            [
                'DIV' => 'auction_lot_admin_tab',
                'SORT' => 1,
                'TAB' => 'Статистика по лоту',
                'TITLE' => 'Статистика по лоту',
            ]
        ] : null;
    }

    /**
     * Отображает контент таба
     *
     * @param string $tabCode - Символьный код таба
     * @param array|mixed[] $elementInfo - Массив, описывающий элемент и инфоблок
     * @return void
     */
    public function showTabContent(string $tabName, array $elementInfo): void
    {
        /** @var AuctionLot $lot - Лот аукциона */
        $lot = AuctionLot::select('PROPERTY_MAXIMUM_BET_AT_THIS_MOMENT', 'PROPERTY_USERS_MADE_MAXIMUM_BET')
            ->filter(['ID' => $elementInfo['ID']])
            ->getList()
            ->first();

        $maximumBet = $lot ? $lot->getMaximumBet() : 'n\a';
        $usersMadeMaximumBetId = $lot ? $lot->getUsersMadeMaximumBetId() : [];

        $content = '<b>Максимальная ставка на текущий момент:</b> ' . $maximumBet . '<hr />';
        $content .= '<b>Пользователи, сделавшие максимальную ставку</b><br/>';
        foreach ($usersMadeMaximumBetId as $key => $user) {
            $content .= ++$key . '. ' . '<a target="_blank" href="' . get_external_url(false, true)
                . '/bitrix/admin/user_edit.php?lang=ru&ID=' . $user . '">ID - ' . $user . '</a><br />';
        }

        echo $content;
    }
}

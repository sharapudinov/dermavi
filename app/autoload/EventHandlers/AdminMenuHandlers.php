<?php

namespace App\EventHandlers;

use App\Models\Auxiliary\Sale\BitrixOrder;
use Bitrix\Main\Config\Option;

class AdminMenuHandlers
{
    public static function addApplicationMenu(&$aGlobalMenu, &$aModuleMenu)
    {
        unset($aGlobalMenu["global_menu_desktop"]);
        
        $items = [];
        $items[] = static::addLevelOneMenu();
        
        $menu = [
            "menu_id" => "app",
            "page_icon" => "services_title_icon",
            "index_icon" => "services_page_icon",
            "text" => Option::get('main', 'site_name', 'Меню приложения'),
            "title" => "Меню приложения",
            "sort" => 50,
            "items_id" => "global_menu_app",
            "help_section" => "app",
            "items" => $items,
        ];
        
        return [
            "global_menu_app" => $menu
        ];
    }
    
    public static function addLevelOneMenu()
    {
        if (!user()->isAdmin()) {
            return [];
        }

        $menu = [
            "parent_menu" => "global_menu_app",
            "icon"        => "iblock_menu_icon_settings",
            "page_icon"   => "default_page_icon",
            "sort"        => 100,
            "text"        => "Тестовое меню",
            "title"       => "Тестовое меню",
            "more_url"    => [],
            "items"       => []
        ];

        $menu['items'][] = [
            "text" => "Тестовая страница",
            "url" => "/bitrix/admin/foo.php",
            "more_url" => array(),
        ];

        return $menu;
    }

    public static function OrderDetailAdminContextMenuShow(&$items)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' &&
            (
                $GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/sale_order_edit.php' ||
                $GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/sale_order_view.php'
            )
            && $_REQUEST['ID'] > 0) {

            $order = BitrixOrder::getById($_REQUEST['ID']);
            if ($order->isPaid()) {
                $items[] = [
                    "TEXT"  => "Возврат",
                    "LINK"  => "javascript:refund_form(".$_REQUEST['ID'].")",
                    "TITLE" => "Возврат",
                    "ICON"  => "adm-btn",
                ];
            }
        }

    }
}

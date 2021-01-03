<?php

namespace App\Core\Auxiliary\BitrixMenu;

/**
 * Класс для описания структуры кастомного меню для логов в админке
 * Class Logs
 * @package App\Core\Auxiliary\BitrixMenu\Auctions
 */
class Logs implements BitrixMenuInterface
{
    /**
     * Возвращает структуру отдельного типа в меню со всеми его пунктами
     *
     * @return array
     */
    public function formMenu(): array
    {
        if (!user()->isAdmin()) {
            return [];
        }

        $menu = [
            "parent_menu" => "global_menu_content",
            "section" => "app",
            "sort" => 49,
            "text" => 'Логи',
            "title" => 'Логи',
            "icon" => "iblock_menu_icon_settings",
            "items_id" => "app_menu",
            "items" => [],
        ];

        $menu["items"][] = [
            "text" => "Выгрузка логов",
            "title" => "Выгрузка логов",
            "url" => "/bitrix/admin/app_admin-logs-watch.php",
            "items_id" => "app_admin-logs-watch",
        ];

        $menu["items"][] = [
            "text" => "Лог запуска команд при изменении файлов",
            "title" => "Лог запуска команд при изменении файлов",
            "url" => "/bitrix/admin/app_admin-logs-file-checker.php",
            "items_id" => "app_admin-logs-file-checker",
        ];

        return $menu;
    }
}

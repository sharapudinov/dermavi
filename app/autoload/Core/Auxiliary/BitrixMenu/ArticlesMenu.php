<?php

namespace App\Core\Auxiliary\BitrixMenu;

/**
 * Класс, описывающий логику обработки меню для статей
 * Class ArticlesMenu
 *
 * @package App\Core\Auxiliary\BitrixMenu
 */
class ArticlesMenu implements BitrixMenuInterface
{
    /**
     * Возвращает структуру отдельного типа в меню со всеми его пунктами
     *
     * @return array
     */
    public function formMenu(): array
    {
        $menu = [
            "parent_menu" => "global_menu_content",
            "section" => "app",
            "sort" => 48,
            "text" => 'Статьи',
            "title" => 'Статьи',
            "icon" => "iblock_menu_icon_settings",
            "items_id" => "app_articles",
            "items" => [],
        ];

        $menu["items"][] = [
            "text" => "Создание статьи",
            "title" => "Создание статьи",
            "url" => "/bitrix/admin/app_article-create.php",
            "items_id" => "app_article-create",
        ];

        $menu["items"][] = [
            "text" => "Редактирование статьи",
            "title" => "Редактирование статьи",
            "url" => "/bitrix/admin/app_article-create.php?edit=true",
            "items_id" => "app_article-create",
        ];

        return $menu;
    }
}

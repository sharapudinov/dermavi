<?php

namespace App\Core\Auxiliary\BitrixMenu;

/**
 * Класс для добавления пунктов меню в админке
 * Class AdminMenu
 * @package App\Core\Auxiliary\BitrixMenu
 */
class AdminMenu
{
    /**
     * Возвращает массив классов, участвующих в построении кастомного меню
     *
     * @return array|string[]
     */
    private static function getClassesForMenu(): array
    {
        return [
            ArticlesMenu::class,
            Auctions::class,
            Cadas::class,
            Diamonds::class,
            Emails::class,
            Jewelry::class,
            Logs::class,
        ];
    }

    /**
     * Добавляет кастомные пункты в меню админки
     *
     * @param array $aGlobalMenu
     * @param array $aModuleMenu
     */
    public static function addApplicationMenu(array &$aGlobalMenu, array &$aModuleMenu)
    {
        foreach (self::getClassesForMenu() as $class) {
            /** @var BitrixMenuInterface $class - Класс, реализующий BitrixMenuInterface и описывающий тип меню */

            $aModuleMenu[] = (new $class())->formMenu();
        }
    }
}

<?php

namespace App\Core\Auxiliary\IblockTabs;

/**
 * Интерфейс для работы с кастомными табами
 * Interface TabInterface
 * @package App\Core\Auxiliary\IblockTabs
 */
interface TabInterface
{
    /**
     * Возвращает описание табов
     *
     * @param array|mixed[] $elementInfo - Массив, описывающий элемент и инфоблок
     * @return array|null
     */
    public function getTabList(array $elementInfo): ?array;

    /**
     * Отображает контент таба
     *
     * @param string $tabCode - Символьный код таба
     * @param array|mixed[] $elementInfo - Массив, описывающий элемент и инфоблок
     * @return void
     */
    public function showTabContent(string $tabCode, array $elementInfo): void;
}

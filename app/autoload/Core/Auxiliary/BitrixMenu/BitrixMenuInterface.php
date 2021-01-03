<?php

namespace App\Core\Auxiliary\BitrixMenu;

/**
 * Интерфейс для работы с кастомным меню в админке
 * Interface BitrixMenuInterface
 * @package App\Core\Auxiliary\BitrixMenu\Auctions
 */
interface BitrixMenuInterface
{
    /**
     * Возвращает структуру отдельного типа в меню со всеми его пунктами
     *
     * @return array
     */
    public function formMenu(): array;
}

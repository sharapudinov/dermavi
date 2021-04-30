<?php

namespace App\Core\Jewelry;

use Arrilot\BitrixCacher\Cache;

/**
 * Класс, описывающий логику взаимодействия с рекомендациями
 * Class JewelryRecommendations
 *
 * @package App\Core\Jewelry
 */
class JewelryRecommendations
{
    /** @var string Ключ кеша компонента рекомендаций ЮБИ */
    public const RECOMMENDED_COMPONENT_CACHE = 'jewelry_recommended';

    /**
     * Сбрасывает кеш компонента рекоммендаций
     *
     * @return void
     */
    public static function flushComponentCache(): void
    {
        Cache::flush(self::RECOMMENDED_COMPONENT_CACHE);
    }
}

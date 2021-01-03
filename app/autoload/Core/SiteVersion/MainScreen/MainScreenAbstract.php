<?php

namespace App\Core\SiteVersion\MainScreen;

use App\Helpers\TTL;

/**
 * Класс, описывающий логику получения данных для главной страницы для нужного типа лица (юр лицо, физ лицо)
 * Class MainScreen
 *
 * @package App\Core\SiteVersion\MainScreen
 */
abstract class MainScreenAbstract
{
    /**
     * Возвращает массив данных, загруженных для главной страницы для нужного типа пользователя (юр лицо, физ лицо)
     *
     * @return array|mixed[]
     */
    public function getGeneralInfo(): array
    {
        /** @var string $cacheKey Ключ кеширования */
        $cacheKey = get_default_cache_key(static::class) . (user() ? '_user_' . user()->getId() : '');
        return cache($cacheKey, TTL::DAY, function () {
            $info = [
                'include' => $this instanceof MainScreenPhysicPerson ? 'b2c': 'b2b'
            ];

            return array_merge($info, $this->getSpecificInfo());
        });
    }

    /**
     * Возвращает массив данных, загруженных для главной страницы для нужного типа пользователя (юр лицо, физ лицо)
     *
     * @return array|mixed[]
     */
    abstract protected function getSpecificInfo(): array;
}

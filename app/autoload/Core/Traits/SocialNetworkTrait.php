<?php

namespace App\Core\Traits;

use App\Core\SprintOptions\SocialNetwork;
use App\Helpers\TTL;

/**
 * Класс-трейт для работы с социальными сетями
 * Trait SocialNetworkTrait
 * @package App\Core\Traits
 */
trait SocialNetworkTrait
{
    /**
     * Получаем ссылки на группы и профили в социальных сетях
     *
     * @return array
     */
    protected function getAllSocialNetworksLinks(): array
    {
        return cache(get_default_cache_key(self::class), TTL::DAY, function () {
            return [
                'facebook' => SocialNetwork::getFacebookAccountLink(),
                'twitter' => SocialNetwork::getTwitterAccountLink(),
                'instagram' => SocialNetwork::getInstagramAccountLink()
            ];
        });
    }
}

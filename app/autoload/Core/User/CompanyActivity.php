<?php

namespace App\Core\User;

use App\Helpers\SiteHelper;
use App\Helpers\TTL;
use App\Models\HL\Company\CompanyActivity as CompanyActivityModel;
use Illuminate\Support\Collection;

/**
 * Класс для работы с направлением компании
 * Class CompanyActivity
 * @package App\Core\User
 */
class CompanyActivity
{
    /** @var Collection $activities - Направления компании */
    private static $activities;

    /**
     * Получает кешированную коллекцию направлений компании на нужном языке
     *
     * @return Collection
     */
    public static function getActivities(): Collection
    {
        if (!self::$activities) {
            self::$activities = cache(
                get_default_cache_key(self::class),
                TTL::DAY,
                function () {
                    return CompanyActivityModel::baseQuery();
                }
            );
        }

        return self::$activities;
    }
}

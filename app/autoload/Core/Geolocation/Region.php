<?php

namespace App\Core\Geolocation;

use App\Helpers\TTL;
use App\Models\Auxiliary\CRM\Region as RegionModel;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику работы с регионами
 * Class Region
 *
 * @package App\Core\Geolocation
 */
class Region
{
    /** @var array|Collection[] */
    private static $regions;

    /**
     * Возвращает коллекцию всех регионов
     *
     * @param int $countryId Идентификатор страны
     *
     * @return void
     */
    private static function loadRegionsForCountry(int $countryId): void
    {
        if (!self::$regions[$countryId]) {
            self::$regions[$countryId] = cache(
                'regions_list_' . $countryId,
                TTL::DAY,
                function () use ($countryId) {
                    return RegionModel::where('country_id', $countryId)->get();
                }
            );
        }
    }

    /**
     * Возвращает модель региона по идентификатору страны и идентификатору региона
     *
     * @param int $countryId Идентификатор страны
     * @param int|null $regionId Идентификатор региона
     *
     * @return RegionModel|null
     */
    public static function getRegionByCountryIdAndRegionId(int $countryId, ?int $regionId): ?RegionModel
    {
        if (!$regionId) {
            return null;
        }

        self::loadRegionsForCountry($countryId);
        return self::$regions[$countryId]->first(function (RegionModel $region) use ($regionId) {
            return $region->getId() == $regionId;
        });
    }
}

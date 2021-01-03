<?php

namespace App\Core\Geolocation;

use App\Helpers\LanguageHelper;
use App\Helpers\TTL;
use App\Models\HL\Country as CountryModel;
use Illuminate\Support\Collection;

/**
 * Класс для работы со странами
 *
 * Class Country
 * @package App\Core\Geolocation
 */
class Country
{
    /** @var Collection $countries - Коллекция стран */
    private static $countries;

    /**
     * Получает кешированный список всех стран на нужном языке
     *
     * @return Collection
     */
    public static function getCountries(): Collection
    {
        $language = strtoupper(LanguageHelper::getLanguageVersion());

        if (!self::$countries) {
            self::$countries = cache(
                get_class_name_without_namespace(self::class) . '_' . $language,
                TTL::DAY,
                function () {
                    return CountryModel::baseQuery();
                }
            );
        }

        return self::$countries;
    }

    /**
     * Возвращает страну из готовой коллекции по ее идентификатору
     *
     * @param Collection $countries Коллекция стран
     * @param int $countryId Идентификатор нужной страны
     *
     * @return CountryModel
     */
    public static function getCountryByIdFromCollection(Collection $countries, int $countryId): CountryModel
    {
        return $countries->filter(function (CountryModel $country) use ($countryId) {
            return $country->getId() == $countryId;
        })->first();
    }
}

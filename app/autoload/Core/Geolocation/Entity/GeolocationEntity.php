<?php

namespace App\Core\Geolocation\Entity;

use App\Models\HL\Country;

/**
 * Класс для сущности "Геолокация"
 * Class GeolocationEntity
 * @package App\Core\Geolocation\Entity
 */
class GeolocationEntity
{
    /**
     * Данные по-умолчанию, если не удалось определить страну по ip
     *
     * @var array
     */
    private $defaultInfo = [
        'country_code' => 'US'
    ];

    /** @var string $countryCode - Код страны */
    private $countryCode;

    /** @var int $toArkhangelsk - Количество километров от пользователя до Архангельска */
    private $toArkhangelsk;

    /** @var int $toArkhangelsk - Количество километров от пользователя до Якутии */
    private $toYakutia;

    /**
     * GeolocationEntity constructor.
     *
     * @param array $geolocationArray - Массив, полученный из модуля greensight.geo
     * @param float|null $toArkhangelsk - Расстояние от пользователя до Архангельска (км)
     * @param float|null $toYakutia - Расстояние от пользователя до Якутиии (км)
     */
    public function __construct(array $geolocationArray, ?float $toArkhangelsk, ?float $toYakutia)
    {
        $this->countryCode = $geolocationArray['country_code'] ?? $this->defaultInfo['country_code'];
        $this->toArkhangelsk = (int) $toArkhangelsk;
        $this->toYakutia = (int) $toYakutia;
    }

    /**
     * Получаем код страны
     *
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * Получает расстояние от пользователя до Архангельска
     *
     * @return int
     */
    public function getDistanceToArkhangelsk(): int
    {
        return $this->toArkhangelsk;
    }

    /**
     * Получает расстояние от пользователя до Якутии
     *
     * @return int
     */
    public function getDistanceToYakutia(): int
    {
        return $this->toYakutia;
    }
}

<?php

namespace App\Models\Auxiliary;

use Arrilot\BitrixModels\Models\EloquentModel;

/**
 * Класс-модель для сущности "Гео зона"
 * Class GeoZone
 * @package App\Models\Auxiliary
 */
class GeoZone extends EloquentModel
{
    /** @var bool $timestamps */
    public $timestamps = false;
    /** @var string $table */
    protected $table = 'geo_zone';

    /**
     * Получаем название зоны
     *
     * @return string
     */
    public function getName(): string
    {
        return $this['zone_name'];
    }

    /**
     * Получаем код страны по ISO-3166
     *
     * @return string
     */
    public function getCountryNumberCode(): string
    {
        return $this['country_number_code'];
    }
}

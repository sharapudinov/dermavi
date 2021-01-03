<?php

namespace App\Core\Geolocation\Map;

use App\Models\Sale\PickupPoint;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику работы с картой
 * Class Map
 *
 * @package App\Core\Geolocation
 */
class Map
{
    /**
     * Возвращает массив, описывающий пункты самовывоза для вывода на карте
     *
     * @param Collection|PickupPoint[] $points Коллекция пунктов самовывоза
     *
     * @return array|array[]
     */
    public static function getMapPoints(Collection $points): array
    {
        /** @var array|mixed[] $out - массив пунктов самовывоза для карты */
        $out = [];

        foreach ($points as $point) {
            $out[] = [
                'id' => $point->getId(),
                'coords' => $point->getCoordinates(),
                'name' => $point->getName(),
                'location' => $point->getLocation(),
                'address' => $point->getAddress(),
                'phone' => $point->getAllPhones(),
                'time' => $point->getWorkingHours(),
                'isSelected' => $points->first()->getId() == $point->getId()
            ];
        }

        return $out;
    }
}

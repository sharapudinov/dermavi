<?php

namespace App\Api\Internal\Sale;

use App\Api\BaseController;
use App\Core\Geolocation\Map\Map;
use App\Models\Sale\PickupPoint;
use Psr\Http\Message\ResponseInterface;

/**
 * Класс-контроллер для работы с пунктами самовывоза
 * Class PickupPointController
 * @package App\Api\Internal\Sale
 */
class PickupPointController extends BaseController
{
    /**
     * Загружаем пункты самовывоза для карты
     *
     * @return ResponseInterface
     */
    public function getList(): ResponseInterface
    {
        $pickupPoints = PickupPoint::baseQuery();
        return $this->response->withJSON(Map::getMapPoints($pickupPoints));
    }
}

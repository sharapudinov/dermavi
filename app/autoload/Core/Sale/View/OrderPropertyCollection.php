<?php

namespace App\Core\Sale\View;

use App\Models\Auxiliary\Sale\BitrixOrderPropertyValue;
use Illuminate\Support\Collection;

/**
 * Класс для работы с коллекцией свойств заказа
 * Class OrderPropertyCollection
 *
 * @package App\Core\Sale\View
 *
 * @method BitrixOrderPropertyValue getDeliveryFirstName
 * @method BitrixOrderPropertyValue getDeliveryLastName
 * @method BitrixOrderPropertyValue getDeliverySecondName
 * @method BitrixOrderPropertyValue getDeliveryPhone
 */
class OrderPropertyCollection
{
    /** @var Collection|BitrixOrderPropertyValue[] $properties Коллекция свойств заказа */
    private $properties;

    /**
     * OrderPropertyCollection constructor.
     *
     * @param Collection|BitrixOrderPropertyValue[] $properties Коллекция свойств заказа
     */
    public function __construct(Collection $properties)
    {
        $this->properties = $properties;
    }

    /**
     * Возвращает модель свойства заказа по его символьному коду
     *
     * @param string $code - Символьный код свойства
     *
     * @return BitrixOrderPropertyValue|null
     */
    public function __get(string $code): ?BitrixOrderPropertyValue
    {
        /** @var string $symCode - Символьный код свойства */
        $symCode = strtoupper(preg_replace('/([a-z]+)([A-Z][a-z])/', '$1_$2', $code));
        $symCode = strpos($symCode, 'GET_') !== false ? substr($symCode, 4) : $symCode;

        return $this->properties->filter(function (BitrixOrderPropertyValue $property) use ($symCode) {
            return $property->getCode() == $symCode;
        })->first();
    }
}

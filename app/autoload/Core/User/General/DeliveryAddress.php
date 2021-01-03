<?php

namespace App\Core\User\General;

use App\Helpers\TTL;
use App\Models\HL\AddressType;
use App\Models\HL\Address as DeliveryAddressModel;
use App\Models\User;

/**
 * Class DeliveryAddress
 * @package App\Core\User\General
 */
class DeliveryAddress
{
    /**
     * Проверяет введенный пользователем адрес на уникальность
     *
     * @param User $user - Пользователь
     * @param array $request - Массив с полями формы
     * @return bool
     */
    public static function isAddressUnique(User $user, array $request): bool
    {
        /** @var array $filter - Фильтр для запроса */
        $filter = [
            'LOGIC' => 'AND',
            ['UF_USER_ID' => $user->getId()],
            ['UF_COUNTRY' => $request['delivery-address-country']],
            ['UF_ZIP' => $request['delivery-address-index']],
            ['UF_REGION' => $request['delivery-address-region']],
            ['UF_CITY' => $request['delivery-address-city']],
            ['UF_STREET' => $request['delivery-address-street']],
            ['UF_HOUSE' => $request['delivery-address-house']],
            ['UF_FLAT' => $request['delivery-address-apartment']],
            ['UF_TYPE_ID' => AddressType::getDeliveryAddressType()->getId()],
        ];
        if ($request['delivery-address-id']) {
            $filter['!ID'] = $request['delivery-address-id'];
        }

        /** @var \App\Models\HL\Address|null $sameDeliveryAddress - Адрес доставки */
        $sameDeliveryAddress = DeliveryAddressModel::filter($filter)->first();

        return $sameDeliveryAddress ? false : true;
    }

    /**
     * Получает массив с данными из формы для запроса по адресам доставки
     *
     * @param User $user - Текущий пользователь
     * @param array $request - Массив полей из формы
     * @return array
     */
    public static function getAddressModelArrayByForm(User $user, array $request): array
    {
        return [
            'UF_USER_ID' => $user->getId(),
            'UF_COUNTRY' => $request['delivery-address-country'],
            'UF_ZIP' => $request['delivery-address-index'],
            'UF_REGION' => $request['delivery-address-region'],
            'UF_CITY' => $request['delivery-address-city'],
            'UF_STREET' => $request['delivery-address-street'],
            'UF_HOUSE' => $request['delivery-address-house'],
            'UF_FLAT' => $request['delivery-address-apartment'],
            'UF_IS_DEFAULT' => $request['delivery-address-agree'] ?? false,
            'UF_TYPE_ID' => AddressType::getDeliveryAddressType()->getId(),
        ];
    }

    /**
     * Реализует логику отметки адреса по-умолчаню
     *
     * @param User $user - Пользователь
     * @return void
     */
    public static function markAddressAsDefault(User $user): void
    {
        /** @var DeliveryAddressModel|null $defaultAddress - Прошлый адрес по-умолчанию */
        $defaultAddress = DeliveryAddressModel::filter([
            'UF_USER_ID' => $user->getId(),
            'UF_IS_DEFAULT' => true
        ])->first();

        if ($defaultAddress) {
            $defaultAddress->update(['UF_IS_DEFAULT' => false]);
        }
    }
}

<?php

namespace App\Core\User\Address;

use App\Core\User\General\Entity\Address;
use App\Helpers\TTL;
use App\Models\HL\AddressType;

/**
 * Class AbstractAddress
 * @package App\Core\User\Address
 */
abstract class AbstractAddress
{
    /**
     * Получает нужный тип адреса для нужного типа лица
     *
     * @param string $addressTypeName - Наименование типа адреса
     *
     * @return AddressType
     */
    protected function getAddressType(string $addressTypeName): AddressType
    {
        return cache(
            get_default_cache_key(self::class) . '_address_type_' . $addressTypeName,
            TTL::DAY,
            function () use ($addressTypeName) {
                /** @var \Illuminate\Support\Collection|AddressType[] $addressTypes - Коллекция типов адресов */
                $addressTypes = AddressType::getList();

                return $addressTypes->filter(function (AddressType $addressType) use ($addressTypeName) {
                    return $addressType->getName() == $addressTypeName;
                })->first();
            }
        );
    }

    /**
     * Возвращает массив адресов доставки для конкретного пользователя
     *
     * @param array|Address[] $addresses - Массив адресов конкретного пользователя
     *
     * @return array
     */
    protected function getDeliveryAddresses(array $addresses): array
    {
        $deliveryAddressType = $this->getAddressType(AddressType::DELIVERY_TYPE);

        $deliveryAddresses = [];
        foreach ($addresses as $address) {
            if ($address->getTypeId() == $deliveryAddressType->getCrmId()) {
                $deliveryAddresses[] = $address;
            }
        }

        return $deliveryAddresses;
    }

    /**
     * Получает главный адрес для нужного типа лица (юр/физ)
     *
     * @return Address
     */
    abstract public function getGeneralAddress(): Address;
}

<?php

namespace App\Helpers;

use App\Core\User\General\Entity\Address;
use App\Models\HL\AddressType;
use App\Models\User;

/**
 * Класс-хелпер для работы с адресами
 * Class AddressHelper
 * @package App\Helpers
 */
class AddressHelper
{
    /**
     * Возвращает массив, описывающий адрес доставки, для создания в БД
     *
     * @param Address $address - Объект, описывающий адрес в CRM
     * @param User $user - Модель пользователя, которому принадлежит адрес
     *
     * @return array|mixed[]
     */
    public static function generateDeliveryAddressDataFromCrmObject(Address $address, User $user): array
    {
        return [
            'UF_USER_ID' => $user->getId(),
            'UF_COUNTRY' => $address->getCountry(),
            'UF_ZIP' => $address->getZip(),
            'UF_CITY' => $address->getCity(),
            'UF_STREET' => $address->getStreet(),
            'UF_HOUSE' => $address->getHouse(),
            'UF_FLAT' => $address->getOffice(),
            'UF_REGION' => $address->getRegion(),
            'UF_TYPE_ID' => AddressType::filter(['UF_CRM_ID' => $address->getTypeId()])->first()->getId(),
            'UF_CRM_ID' => $address->getCrmId()
        ];
    }
}

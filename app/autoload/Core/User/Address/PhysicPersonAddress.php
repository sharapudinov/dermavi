<?php

namespace App\Core\User\Address;

use App\Core\User\General\Entity\Address;
use App\Core\User\PhysicPerson\Entity\PhysicEntity;
use App\Models\HL\AddressType;

/**
 * Класс для работы с основным адресом физ лица
 * Class PhysicPersonGeneralAddress
 * @package App\Core\User\Address
 */
class PhysicPersonAddress extends AbstractAddress
{
    /** @var PhysicEntity $crmUser - Объект, описывающий физ лицо */
    private $crmUser;

    /**
     * Записывает в свойство класса объект, описывающий физ лицо
     *
     * @param PhysicEntity $crmUser - Объект, описывающий физ лицо
     *
     * @return PhysicPersonAddress
     */
    public function setUser(PhysicEntity $crmUser): self
    {
        $this->crmUser = $crmUser;
        return $this;
    }

    /**
     * Получает главный адрес для нужного типа лица (юр/физ)
     *
     * @return Address
     */
    public function getGeneralAddress(): Address
    {
        $homeAddressType = $this->getAddressType(AddressType::PHYSIC_TYPE);

        /** @var Address $homeAddress - Домашний адрес пользователя */
        $homeAddress = null;
        foreach ($this->crmUser->getAddress() as $address) {
            if ($address->getTypeId() == $homeAddressType->getCrmId()) {
                $homeAddress = $address;
                break;
            }
        }

        return $homeAddress;
    }

    /**
     * Возвращает адреса доставки для пользователя
     *
     * @return array
     */
    public function getDeliveryAddresses(): array
    {
        return parent::getDeliveryAddresses($this->crmUser->getAddress());
    }
}
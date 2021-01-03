<?php

namespace App\Core\User\Address;

use App\Core\User\General\Entity\Address;
use App\Core\User\LegalPerson\Entity\LegalEntity;
use App\Models\HL\AddressType;

/**
 * Класс для работы с основным адресом юр лица
 * Class LegalPersonGeneralAddress
 * @package App\Core\User\Address
 */
class LegalPersonAddress extends AbstractAddress
{
    /** @var LegalEntity $crmUser - Объект, описывающий юр лицо */
    private $crmUser;

    /**
     * Записывает в свойство класса объект, описывающий физ лицо
     *
     * @param LegalEntity $crmUser - Объект, описывающий физ лицо
     *
     * @return LegalPersonAddress
     */
    public function setUser(LegalEntity $crmUser): self
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
        $legalAddressType = $this->getAddressType(AddressType::LEGAL_TYPE);

        /** @var Address $legalAddress - Юридический адрес пользователя */
        $legalAddress = null;
        foreach ($this->crmUser->getAddress() as $address) {
            if ($address->getTypeId() == $legalAddressType->getCrmId()) {
                $legalAddress = $address;
                break;
            }
        }

        return $legalAddress;
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

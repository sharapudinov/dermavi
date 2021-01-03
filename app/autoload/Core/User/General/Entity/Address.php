<?php

namespace App\Core\User\General\Entity;

use App\Helpers\TTL;
use App\Models\Auxiliary\CRM\Region;
use App\Models\HL\Address as AddressModel;
use App\Models\HL\Company\Company;
use App\Models\HL\Country;
use App\Models\User;
use stdClass;

/**
 * Класс для описания сущности "Адресные данные"
 * Class Address
 * @package App\Core\User\LegalPerson\Entity
 */
class Address
{
    /** @var string|null $ID - CRM ID */
    private $Id;

    /** @var string|null $TypeId - Тип адреса */
    private $TypeId;

    /** @var string|null $Country - Страна */
    private $Country;

    /** @var string|null $City - Город */
    private $City;

    /** @var string|null $Street - Улица */
    private $Street;

    /** @var string|null $Region - Регион (ALR-89) */
    private $Region;

    /** @var string|null $District - Район*/
    private $District;

    /** @var string|null $House - Номер дома */
    private $House;

    /** @var int|null $Building - Строение */
    private $Building;

    /** @var int|null $Floor - Этаж */
    private $Floor;

    /** @var string|null $Office - Офис */
    private $Office;

    /** @var string|null $Zip - Индекс */
    private $Zip;

    /** @var string|null $OKTMO - ОКТМО */
    private $OKTMO;

    /** @var string|null $Phone - Телефон */
    private $Phone;

    /** @var string|null $Email - Email */
    private $Email;

    /**
     * Заполняет объект данными пользователя из БД как для физ лица
     *
     * @param User $user - Модель пользователя
     *
     * @return void
     */
    private function setAsPhysicPerson(User $user): void
    {
        /** @var Region $region */
        $region = Region::where('id', $user->physicAddress->getRegionId())->first();

        $this->Id = $user->physicAddress->getCrmId();
        $this->TypeId = $user->physicAddress->type->getCrmId();
        $this->Country = $user->physicAddress->country->getCrmId();
        $this->City = $user->physicAddress->getCity();
        $this->Street = $user->physicAddress->getStreet();
        $this->Region = $region ? $region->getCrmId() : null;
        $this->House = $user->physicAddress->getHouse();
        $this->Office = $user->physicAddress->getFlat();
        $this->Zip = $user->physicAddress->getZip();
        $this->Phone = $user->getPhone();
        $this->Email = $user->getEmail();
    }

    /**
     * Заполняет объект данными пользователя из БД как для юр лица
     *
     * @param Company $company - Модель компании
     *
     * @return void
     */
    private function setAsLegalPerson(Company $company): void
    {
        /** @var Region $region - Модель региона */
        $region = Region::where('id', $company->address->getRegion())->first();

        $this->Id = $company->address->getCrmId();
        $this->TypeId = $company->address->type->getCrmId();
        $this->Country = $company->address->country->getCrmId();
        $this->City = $company->address->getCity();
        $this->Street = $company->address->getStreet();
        $this->Region = $region ? $region->getCrmId() : null;
        $this->District = null;
        $this->House = $company->address->getHouse();
        $this->Building = null;
        $this->Floor = null;
        $this->Office = $company->address->getFlat();
        $this->Zip = $company->address->getZip();
        $this->Phone = $company->getPhone();
        $this->Email = $company->getEmail();
    }

    /**
     * Записывает в объект данные о компани на основе данных из CRM
     *
     * @param stdClass $address - Объект, описывающий адрес из CRM
     *
     * @return Address
     */
    public function setFromCrm(stdClass $address): self
    {
        /** @var Country $country - Модель страны пользователя */
        $country = Country::cache(TTL::DAY)->filter(['UF_CRM_ID' => $address->Country])->first();

        $this->Id = $address->Id;
        $this->TypeId = $address->TypeId;
        $this->Country = $country ? $country->getId() : null;
        $this->City = $address->City;
        $this->Street = $address->Street;
        $this->House = $address->House;
        $this->Office = $address->Office;
        $this->Zip = $address->Zip;
        $this->OKTMO = $address->OKTMO;
        $this->Phone = $address->Phone;
        $this->Email = $address->Email;

        /** @var Region $region - Модель региона */
        $region = Region::where('code', $address->Region)->first();
        $this->Region = $region ? $region->getId() : null;

        return $this;
    }

    /**
     * Записывает в объект данные о компании на основе данных из БД ИМ
     *
     * @param User $user - Модель пользователя
     *
     * @return self
     */
    public function setFromUser(User $user): self
    {
        if ($user->isLegalEntity()) {
            $this->setAsLegalPerson($user->company);
        } else {
            $this->setAsPhysicPerson($user);
        }

        return $this;
    }

    /**
     * Записывает в объект данные адреса доставки
     *
     * @param AddressModel $deliveryAddress - Модель адреса
     *
     * @return Address
     */
    public function setFromDeliveryAddress(AddressModel $deliveryAddress): self
    {
        /** @var Region $region - Модель региона */
        $region = Region::where('id', $deliveryAddress->getRegionId())->first();

        $this->Id = $deliveryAddress->getCrmId();
        $this->TypeId = $deliveryAddress->type->getCrmId();
        $this->Country = $deliveryAddress->country->getCrmId();
        $this->City = $deliveryAddress->getCity();
        $this->Street = $deliveryAddress->getStreet();
        $this->Zip = $deliveryAddress->getZip();
        $this->Region = $region->getCrmId();
        $this->House = $deliveryAddress->getHouse();
        $this->Office = $deliveryAddress->getFlat();

        return $this;
    }


    /**
     * Получаем ID CRM
     *
     * @return null|string
     */
    public function getCrmId(): ?string
    {
        return $this->Id;
    }

    /**
     * Получаем тип адреса
     *
     * @return string|null
     */
    public function getTypeId(): ?string
    {
        return $this->TypeId;
    }

    /**
     * Получаем страну
     *
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->Country;
    }

    /**
     * Получаем город
     *
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->City;
    }

    /**
     * Получаем улицу
     *
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->Street;
    }

    /**
     * Получаем регион
     *
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->Region;
    }

    /**
     * Получаем номер дома
     *
     * @return string|null
     */
    public function getHouse(): ?string
    {
        return $this->House;
    }

    /**
     * Получаем номер строения
     *
     * @return int|null
     */
    public function getBuilding(): ?int
    {
        return $this->Building;
    }

    /**
     * Получаем этаж
     *
     * @return int|null
     */
    public function getFloor(): ?int
    {
        return $this->Floor;
    }

    /**
     * Получаем номер офиса
     *
     * @return string|null
     */
    public function getOffice(): ?string
    {
        return $this->Office;
    }

    /**
     * Получаем индекс
     *
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->Zip;
    }

    /**
     * Получаем ОКТМО
     *
     * @return string|null
     */
    public function getOKTMO(): ?string
    {
        return $this->OKTMO;
    }

    /**
     * Получаем номер телефона
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->Phone;
    }

    /**
     * Возвращает email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->Email;
    }
}

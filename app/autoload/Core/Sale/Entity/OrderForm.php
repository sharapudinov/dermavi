<?php

namespace App\Core\Sale\Entity;

use App\Models\HL\Country;
use App\Models\Sale\PickupPoint;
use Bitrix\Main\Diag\Debug;
use Illuminate\Support\Collection;

/**
 * Класс для описания свойств заказа
 * Class OrderProperty
 * @package App\Core\Sale\Entity
 */
class OrderForm
{
    /**
     * @var array - соответствие свойств формы свойствам заказа
     */
    private static $mapProperties = [
        'companyName'       => 'COMPANY_NAME',
        'clientName'        => 'CLIENT_NAME',
        'taxId'             => 'TAX_ID',
        'country'           => 'COUNTRY',
        'companySpeciality' => 'COMPANY_SPECIALITY',
        'email'             => 'EMAIL',
        'phone'             => 'PHONE',
        'deliveryZip'       => 'ZIP',
        'deliveryCity'      => 'CITY',
        'deliveryRegion'    => 'REGION',
        'deliveryFIO'       => 'FIO',
        'deliveryPickupPointId',
        'deliveryPickupPoint',
        'deliveryCountryId',
        'deliveryCountry',
        'deliveryStreet',
        'deliveryHouse',
        'deliveryFlat',
        'deliveryBirthday',
        'deliveryPhone',
        'deliveryDate',
        'deliveryTime',
        'billingZip',
        'billingCountryId',
        'billingCountry',
        'billingRegion',
        'billingCity',
        'billingStreet',
        'billingHouse',
        'billingFlat',
        'billingFirstName',
        'billingLastName',
        'billingSecondName',
        'billingBirthday',
        'billingPhone',
    ];
    /**
     * @var int - идентификатор службы доставки
     */
    private $deliveryServiceId;

    /**
     * @var bool $toDoor Флаг необходимости доставки до двери
     */
    private $toDoor;

    /**
     * @var int - идентификатор пункта самовывоза
     */
    private $deliveryPickupPointId;
    /**
     * @var string - название и адрес пункта самовывоза
     */
    private $deliveryPickupPoint;
    /**
     * @var int - идентификатор платежной системы
     */
    private $paySystemId;
    /**
     * @var string - почтовый индекс для доставки
     */
    private $deliveryZip;
    /**
     * @var int - идентификатор страны для доставки
     */
    private $deliveryCountryId;
    /**
     * @var string - название страны для доставки
     */
    private $deliveryCountry;
    /**
     * @var string - штат/регион/провинция для доставки
     */
    private $deliveryRegion;
    /**
     * @var string - город для доставки
     */
    private $deliveryCity;
    /**
     * @var string - улица для доставки
     */
    private $deliveryStreet;
    /**
     * @var string - дом/корпус для доставки
     */
    private $deliveryHouse;
    /**
     * @var string - квартира/офис для доставки
     */
    private $deliveryFlat;
    /**
     * @var string - имя получателя для доставки/самовывоза
     */
    private $deliverydeliveryFIO;
    /**
     * @var string - фамилия получателя для доставки/самовывоза
     */
    private $deliveryFirstName;
    /**
     * @var string - фамилия получателя для доставки/самовывоза
     */
    private $deliveryLastName;
    /**
     * @var string - отчество получателя для доставки/самовывоза
     */
    private $deliverySecondName;
    /**
     * @var string - дата рождения получателя для доставки/самовывоза
     */
    private $deliveryBirthday;
    /**
     * @var string - телефон получателя для доставки/самовывоза
     */
    private $deliveryPhone;
    /**
     * @var string - дата для доставки/самовывоза
     */
    private $deliveryDate;
    /**
     * @var string - время для доставки/самовывоза
     */
    private $deliveryTime;
    /**
     * @var string - почтовый индекс для счёта
     */
    private $billingZip;
    /**
     * @var string - идентификатор страны для счёта
     */
    private $billingCountryId;
    /**
     * @var string - название страны для счёта
     */
    private $billingCountry;
    /**
     * @var string - штат/регион/провинция для счёта
     */
    private $billingRegion;
    /**
     * @var string - город для счёта
     */
    private $billingCity;
    /**
     * @var string - улица для счёта
     */
    private $billingStreet;
    /**
     * @var string - дом/корпус для счёта
     */
    private $billingHouse;
    /**
     * @var string - квартира/офис для счёта
     */
    private $billingFlat;
    /**
     * @var string - имя получателя для счёта
     */
    private $billingFirstName;
    /**
     * @var string - фамилия получателя для счёта
     */
    private $billingLastName;
    /**
     * @var string - отчество получателя для счёта
     */
    private $billingSecondName;
    /**
     * @var string - дата рождения получателя для счёта
     */
    private $billingBirthday;
    /**
     * @var string - телефон получателя для счёта
     */
    private $billingPhone;

    /**
     * @var string - email для отправки чеков
     */
    private $email;

    /**
     * @var Collection|Country[] - страны
     */
    private static $countries;

    /**
     * Получаем все свойства класса с их значениями
     *
     * @return array
     */
    public function getAllProperties(): array
    {
        return get_object_vars($this);
    }

    /**
     * @param int $deliveryServiceId
     * @return self
     */
    public function setDeliveryServiceId(int $deliveryServiceId): self
    {
        $this->deliveryServiceId = e($deliveryServiceId);

        return $this;
    }

    /**
     * @return int
     */
    public function getDeliveryServiceId(): int
    {
        return (int)$this->deliveryServiceId;
    }

    /**
     * Записывает флаг необходимости доставки до двери
     *
     * @param bool $toDoor Флаг необходимости доставки до двери
     *
     * @return OrderForm
     */
    public function setToDoor(bool $toDoor): self
    {
        $this->toDoor = $toDoor;
        return $this;
    }

    /**
     * Возвращает флаг необходимости доставки до двери
     *
     * @return bool
     */
    public function isToDoor(): bool
    {
        return $this->toDoor;
    }

    /**
     * Записывает email
     *
     * @return OrderForm
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param int $deliveryPickupPointId
     * @return self
     */
    public function setDeliveryPickupPointId(int $deliveryPickupPointId): self
    {
        $this->deliveryPickupPointId = e($deliveryPickupPointId);

        $pickupPoints = PickupPoint::baseQuery();
        if ($pickupPoints->has($this->deliveryPickupPointId)) {
            $pickupPoint = $pickupPoints[$this->deliveryPickupPointId];
            $this->deliveryPickupPoint = $pickupPoint->getCity() . ' ' . $pickupPoint->getAddress();
        }

        return $this;
    }

    /**
     * Записывает адрес ПВЗ
     *
     * @param string $point Адрес ПВЗ
     *
     * @return OrderForm
     */
    public function setDeliveryPickupPoint(?string $point): self
    {
        $this->deliveryPickupPoint = $point;
        return $this;
    }

    /**
     * @param int $paySystemId
     * @return self
     */
    public function setPaySystemId(int $paySystemId): self
    {
        $this->paySystemId = e($paySystemId);

        return $this;
    }

    /**
     * @return self
     */
    public function getPaySystemId(): int
    {
        return (int)$this->paySystemId;
    }

    /**
     * @param string $deliveryZip
     * @return self
     */
    public function setDeliveryZip(string $deliveryZip): self
    {
        $this->deliveryZip = e($deliveryZip);

        return $this;
    }

    /**
     * @param int $deliveryCountryId
     * @return self
     */
    public function setDeliveryCountryId(int $deliveryCountryId): self
    {
        $this->deliveryCountryId = e($deliveryCountryId);

        $countries = static::getCountries();
        if ($countries->has($this->deliveryCountryId)) {
            $this->deliveryCountry = $countries[$this->deliveryCountryId]->getName();
        }

        return $this;
    }

    /**
     * @param string $deliveryRegion
     * @return self
     */
    public function setDeliveryRegion(string $deliveryRegion): self
    {
        $this->deliveryRegion = e($deliveryRegion);

        return $this;
    }

    /**
     * @param string $deliveryCity
     * @return self
     */
    public function setDeliveryCity(string $deliveryCity): self
    {
        $this->deliveryCity = e($deliveryCity);

        return $this;
    }

    /**
     * Возвращает город доставки
     *
     * @return string|null
     */
    public function getDeliveryCity(): ?string
    {
        return $this->deliveryCity;
    }

    /**
     * @param string $deliveryStreet
     * @return self
     */
    public function setDeliveryStreet(string $deliveryStreet): self
    {
        $this->deliveryStreet = e($deliveryStreet);

        return $this;
    }

    /**
     * @param string $deliveryHouse
     * @return self
     */
    public function setDeliveryHouse(string $deliveryHouse): self
    {
        $this->deliveryHouse = e($deliveryHouse);

        return $this;
    }

    /**
     * @param string $deliveryFlat
     * @return self
     */
    public function setDeliveryFlat(string $deliveryFlat): self
    {
        $this->deliveryFlat = e($deliveryFlat);

        return $this;
    }

    /**
     * @param string $deliveryFIO
     * @return self
     */
    public function setDeliveryFirstName(string $deliveryFIO): self
    {
        $this->deliveryFIO = e($deliveryFIO);

        return $this;
    }

    /**
     * @param string $deliveryFirstName
     * @return self
     */
    public function setDeliveryFIO(string $deliveryFirstName): self
    {
        $this->deliveryFirstName = e($deliveryFirstName);

        return $this;
    }

    /**
     * @param string $deliveryLastName
     * @return self
     */
    public function setDeliveryLastName(string $deliveryLastName): self
    {
        $this->deliveryLastName = e($deliveryLastName);

        return $this;
    }

    /**
     * @param string $deliverySecondName
     * @return self
     */
    public function setDeliverySecondName(string $deliverySecondName): self
    {
        $this->deliverySecondName = e($deliverySecondName);

        return $this;
    }

    /**
     * @param string $deliveryBirthday
     * @return self
     */
    public function setDeliveryBirthday(string $deliveryBirthday): self
    {
        $this->deliveryBirthday = e($deliveryBirthday);

        return $this;
    }

    /**
     * @param string $deliveryPhone
     * @return self
     */
    public function setDeliveryPhone(string $deliveryPhone): self
    {
        $this->deliveryPhone = e($deliveryPhone);

        return $this;
    }

    /**
     * @param string $deliveryDate
     * @return self
     */
    public function setDeliveryDate(string $deliveryDate): self
    {
        $this->deliveryDate = e($deliveryDate);

        return $this;
    }

    /**
     * @param string $deliveryTime
     * @return self
     */
    public function setDeliveryTime(string $deliveryTime): self
    {
        $this->deliveryTime = e($deliveryTime);

        return $this;
    }

    /**
     * @param string $billingZip
     * @return self
     */
    public function setBillingZip(string $billingZip): self
    {
        $this->billingZip = e($billingZip);

        return $this;
    }

    /**
     * @param string $billingCountryId
     * @return self
     */
    public function setBillingCountryId(string $billingCountryId): self
    {
        $this->billingCountryId = e($billingCountryId);

        $countries = static::getCountries();
        if ($countries->has($this->billingCountryId)) {
            $this->billingCountry = $countries[$this->billingCountryId]->getName();
        }

        return $this;
    }

    /**
     * @param string $billingRegion
     * @return self
     */
    public function setBillingRegion(string $billingRegion): self
    {
        $this->billingRegion = e($billingRegion);

        return $this;
    }

    /**
     * @param string $billingCity
     * @return self
     */
    public function setBillingCity(string $billingCity): self
    {
        $this->billingCity = e($billingCity);

        return $this;
    }

    /**
     * @param string $billingStreet
     * @return self
     */
    public function setBillingStreet(string $billingStreet): self
    {
        $this->billingStreet = e($billingStreet);

        return $this;
    }

    /**
     * @param string $billingHouse
     * @return self
     */
    public function setBillingHouse(string $billingHouse): self
    {
        $this->billingHouse = e($billingHouse);

        return $this;
    }

    /**
     * @param string $billingFlat
     * @return self
     */
    public function setBillingFlat(string $billingFlat): self
    {
        $this->billingFlat = e($billingFlat);

        return $this;
    }

    /**
     * @param string $billingFirstName
     * @return self
     */
    public function setBillingFirstName(string $billingFirstName): self
    {
        $this->billingFirstName = e($billingFirstName);

        return $this;
    }

    /**
     * @param string $billingLastName
     * @return self
     */
    public function setBillingLastName(string $billingLastName): self
    {
        $this->billingLastName = e($billingLastName);

        return $this;
    }

    /**
     * @param string $billingSecondName
     * @return self
     */
    public function setBillingSecondName(string $billingSecondName): self
    {
        $this->billingSecondName = e($billingSecondName);

        return $this;
    }

    /**
     * @param string $billingBirthday
     * @return self
     */
    public function setBillingBirthday(string $billingBirthday): self
    {
        $this->billingBirthday = e($billingBirthday);

        return $this;
    }

    /**
     * @param string $billingPhone
     * @return self
     */
    public function setBillingPhone(string $billingPhone): self
    {
        $this->billingPhone = e($billingPhone);

        return $this;
    }

    /**
     * Получить список стран
     * @return Collection
     */
    protected static function getCountries(): Collection
    {
        if (is_null(static::$countries)) {
            static::$countries = Country::baseQuery();
        }

        return static::$countries;
    }

    /**
     * Получить массив соответствия свойств формы свойствам заказа
     * @return array
     */
    public static function getMapProperties(): array
    {
        $mapProperties = [];
        foreach (self::$mapProperties as $key => $value) {
            if (gettype($key) != 'integer') {
                $mapProperties[$key] = $value;
            }
        }

        return $mapProperties;
    }
}

<?php

namespace App\Models\HL;

use App\Models\Auxiliary\CRM\Region;
use App\Models\Auxiliary\HlD7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Illuminate\Support\Collection;

/**
 * Класс-модель для сущности "Адрес доставки"
 * Class DeliveryAddress
 * @package App\Models\HL
 * @property Country $country - страна
 */
class DeliveryAddress extends HlD7Model
{
    /** @var string - InitDir кеша для списка адресов в личном кабинете */
    const DELIVERY_ADDRESS_PERSONAL_CACHE_INIT_DIR = 'DeliveryAddressesPersonal_';

    /** @var string Название таблицы в БД */
    public static $tableName = 'app_delivery_address';

    /** @var Region $region - Модель региона */
    private $region;

    /**
     * Получить адреса доставки для пользователя
     * @param int $userID
     * @return Collection|self[]
     */
    public static function getByUserID(int $userID): Collection
    {
        return self::query()
            ->filter(['UF_USER_ID' => $userID])
            ->getList();
    }

    /**
     * Получить идентификатор пользователя
     * @return int
     */
    public function getUserID(): int
    {
        return (int)$this['UF_USER_ID'];
    }

    /**
     * Получить идентификатор страны
     * @return int
     */
    public function getCountry(): int
    {
        return (int)$this['UF_COUNTRY'];
    }

    /**
     * Получить почтовый индекс
     * @return string
     */
    public function getIndex(): string
    {
        return (string)$this['UF_INDEX'];
    }

    /**
     * Записывает в свойство класса модель региона
     *
     * @param Region $region - Модель региона
     *
     * @return void
     */
    public function setRegion(Region $region): void
    {
        $this->region = $region;
    }

    /**
     * Получить штат/регион/провинцию
     *
     * @return int|null
     */
    public function getRegionId(): ?int
    {
        return $this['UF_REGION'];
    }

    /**
     * Возвращает модель региона адреса доставки
     *
     * @return Region|null
     */
    public function getRegion(): ?Region
    {
        return $this->region;
    }

    /**
     * Получить город
     * @return string
     */
    public function getCity(): string
    {
        return (string)$this['UF_CITY'];
    }

    /**
     * Получить улицу
     * @return string
     */
    public function getStreet(): string
    {
        return (string)$this['UF_STREET'];
    }

    /**
     * Получить дом/корпус
     * @return string
     */
    public function getHouse(): string
    {
        return (string)$this['UF_HOUSE'];
    }

    /**
     * Получить квартиру/офис
     * @return string
     */
    public function getFlat(): string
    {
        return (string)$this['UF_FLAT'];
    }

    /**
     * Адрес доставки является адресом по-умолчанию?
     * @return bool
     */
    public function isDefault(): bool
    {
        return (bool)$this['UF_IS_DEFAULT'];
    }

    /**
     * Сделать адрес доставки адресом по-умолчанию
     */
    public function markAsDefault(): void
    {
        $this['UF_IS_DEFAULT'] = 1;
    }

    /**
     * Получает страну, привязанную к адресу
     *
     * @return BaseQuery
     */
    public function country(): BaseQuery
    {
        return $this->hasOne(Country::class, 'ID', 'UF_COUNTRY');
    }
}

<?php

namespace App\Models\HL\Company;

use App\Models\HL\Address;
use App\Models\HL\Bank;
use App\Models\HL\Country;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Illuminate\Support\Collection;

/**
 * Класс-модель для описания сущности "Страна"
 * Class Company
 *
 * @package App\Models\HL
 *
 * @property-read CompanyActivity $companyActivity
 * @property-read Address $address
 * @property-read Address $locationAddress
 * @property-read Address $mailAddress
 * @property-read Bank $bank
 * @property-read Country $country
 * @property-read Collection|Licence[] $licences
 * @property-read CompanyType $type
 * @property-read CompanyCategory category
 */
class Company extends D7Model
{
    /** @var string Символьный код таблицы */
    public const TABLE_CODE = 'app_company';

    /**
     * Возвращает класс таблицы
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * Возвращает идентификатор компании
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Возвращает полное наимнование компании
     *
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this['UF_FULL_NAME'];
    }

    /**
     * Возвращает название компании
     *
     * @return string
     */
    public function getName(): string
    {
        return $this['UF_NAME'];
    }

    /**
     * Возвращает идентификатор деятельности компании
     *
     * @return string
     */
    public function getActivityID(): string
    {
        return (int)$this['UF_ACTIVITY_ID'];
    }

    /**
     * Возвращает email компании
     *
     * @return string
     */
    public function getEmail(): string
    {
        return (string)$this['UF_EMAIL'];
    }

    /**
     * Возвращает телефон компании
     *
     * @return string
     */
    public function getPhone(): string
    {
        return (string)$this['UF_PHONE'];
    }

    /**
     * Возвращает идентификатор страны компании
     *
     * @return string
     */
    public function getCountry(): string
    {
        return (string)$this['UF_COUNTRY'];
    }

    /**
     * Возвращает ИНН
     *
     * @return string|null
     */
    public function getTaxNumber(): ?string
    {
        return $this['UF_TAX_NUMBER'];
    }

    /**
     * Возвращает КПП компании
     *
     * @return string|null
     */
    public function getKpp(): ?string
    {
        return $this['UF_KPP'];
    }

    /**
     * Возвращает ОКПО компании
     *
     * @return string|null
     */
    public function getOkpo(): ?string
    {
        return $this['UF_OKPO'];
    }

    /**
     * Возвращает ОГРН компании
     *
     * @return string|null
     */
    public function getOgrn(): ?string
    {
        return $this['UF_OGRN'];
    }

    /**
     * Возвращает идентификатор страны регистрации компании
     *
     * @return int|null
     */
    public function getRegisterCountryId(): ?int
    {
        return $this['UF_REG_COUNTRY'];
    }

    /**
     * Возвращает место регистрации компании
     *
     * @return string|null
     */
    public function getRegisterPlace(): ?string
    {
        return $this['UF_REG_PLACE'];
    }

    /**
     * Возвращает орган регистрации компании
     *
     * @return string|null
     */
    public function getRegisterAuthority(): ?string
    {
        return $this['UF_REG_AUTHORITY'];
    }

    /**
     * Возвращает дату регистрации компании
     *
     * @param string $format Формат
     *
     * @return string|null
     */
    public function getRegisterDate(string $format = 'd.m.Y'): ?string
    {
        return $this['UF_REG_DATE'] ? $this['UF_REG_DATE']->format($format) : null;
    }

    /**
     * Возвращает документы, подтверждающий полномочия
     *
     * @return string|null
     */
    public function getAuthorityDocuments(): ?string
    {
        return $this['UF_AUTHORITY_DOCS'];
    }

    /**
     * Возвращает модель сферы деятельности компании
     *
     * @return BaseQuery
     */
    public function companyActivity(): BaseQuery
    {
        return $this->hasOne(CompanyActivity::class, 'ID', 'UF_ACTIVITY_ID');
    }

    /**
     * Получает модель страны, в которой зарегистрирована компания
     *
     * @return BaseQuery
     */
    public function country(): BaseQuery
    {
        return $this->hasOne(Country::class, 'ID', 'UF_REG_COUNTRY');
    }

    /**
     * Возвращает модель юридического адреса компании
     *
     * @return BaseQuery
     */
    public function address(): BaseQuery
    {
        return $this->hasOne(Address::class, 'ID', 'UF_ADDRESS_ID');
    }

    /**
     * Возвращает запрос на получение модели адреса места нахождения компании
     *
     * @return BaseQuery
     */
    public function locationAddress(): BaseQuery
    {
        return $this->hasOne(Address::class, 'ID', 'UF_LOCATION_ADDR');
    }

    /**
     * Возвращает запрос на получение модели почтового адреса
     *
     * @return BaseQuery
     */
    public function mailAddress(): BaseQuery
    {
        return $this->hasOne(Address::class, 'ID', 'UF_POST_ADDR');
    }

    /**
     * Возвращает модель банковских реквизитов компании
     *
     * @return BaseQuery
     */
    public function bank(): BaseQuery
    {
        return $this->hasOne(Bank::class, 'ID', 'UF_BANK_ID');
    }

    /**
     * Возвращает запрос на получение коллекции моделей лицензий компании
     *
     * @return BaseQuery
     */
    public function licences(): BaseQuery
    {
        return $this->hasMany(Licence::class, 'UF_COMPANY_ID', 'ID');
    }

    /**
     * Возвращает запрос на получение модели типа компании (юр лицо, ип)
     *
     * @return BaseQuery
     */
    public function type(): BaseQuery
    {
        return $this->hasOne(CompanyType::class, 'ID', 'UF_TYPE_ID');
    }

    /**
     * Возвращает запрос для получения модели категории
     *
     * @return BaseQuery
     */
    public function category(): BaseQuery
    {
        return $this->hasOne(CompanyCategory::class, 'ID', 'UF_CATEGORY_ID');
    }
}

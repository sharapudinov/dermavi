<?php

namespace App\Models\HL;

use App\Models\HL\Company\Company;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Illuminate\Support\Collection;

/**
 * Класс-модель, описывающий сущность "Бенефициар"
 * Class Beneficiary
 *
 * @package App\Models\HL
 *
 * @property-read UserPersonType personType
 * @property-read PassportData passportData
 * @property-read PublicOfficial publicOfficial
 * @property-read Company company
 * @property-read Collection|Beneficiary[] childBeneficiaries
 * @property-read Beneficiary parentBeneficiary
 */
class Beneficiary extends D7Model
{
    /** @var string - Символьный код таблицы */
    public const TABLE_CODE = 'beneficiary';

    /**
     * Получает класс таблицы
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * Возвращает идентификатор записи
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Возвращает имя бенефициара
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this['UF_NAME'];
    }

    /**
     * Возвращает фамилию бенефициара
     *
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this['UF_SURNAME'];
    }

    /**
     * Возвращает отчество бенефициара
     *
     * @return string|null
     */
    public function getMiddleName(): ?string
    {
        return $this['UF_MIDDLE_NAME'];
    }

    /**
     * Получает полное имя бенефициара
     *
     * @param string|null $language Язык, на котором необходимо получить имя
     *
     * @return null|string
     */
    public function getFullName(): ?string
    {
        return $this->getName() . ' ' . $this->getSurname();
    }

    /**
     * Получает полное имя пользователя c отчеством
     *
     * @return null|string
     */
    public function getFIO(): ?string
    {
        return $this->getSurname() . ' ' . $this->getName() . ($this->getMiddleName() ? ' ' . $this->getMiddleName() : '');
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
     * Возвращает долю участия
     *
     * @return int|null
     */
    public function getShare(): ?int
    {
        return (int) $this['UF_SHARE'];
    }

    /**
     * Возвращает crm id
     *
     * @return string|null
     */
    public function getCrmId(): ?string
    {
        return $this['UF_CRM_ID'];
    }

    /**
     * Возвращает запрос на получение типа лица
     *
     * @return BaseQuery
     */
    public function personType(): BaseQuery
    {
        return $this->hasOne(UserPersonType::class, 'ID', 'UF_PERSON_TYPE_ID');
    }

    /**
     * Возвращает запрос для получения модели паспортных данных
     *
     * @return BaseQuery
     */
    public function passportData(): BaseQuery
    {
        return $this->hasOne(PassportData::class, 'ID', 'UF_PASSPORT_DATA_ID');
    }

    /**
     * Возвращает запрос для получения модели публичного должностного лица
     *
     * @return BaseQuery
     */
    public function publicOfficial(): BaseQuery
    {
        return $this->hasOne(PublicOfficial::class, 'ID', 'UF_PUBLIC_OFFICIAL');
    }

    /**
     * Возвращает запрос для получения модели компании
     *
     * @return BaseQuery
     */
    public function company(): BaseQuery
    {
        return $this->hasOne(Company::class, 'ID', 'UF_COMPANY_ID');
    }

    /**
     * Возвращает запрос для получения коллекции моделей дочерних бенефициаров
     *
     * @return BaseQuery
     */
    public function childBeneficiaries(): BaseQuery
    {
        return $this->hasMany(Beneficiary::class, 'UF_PARENT_ID', 'ID');
    }

    /**
     * Возвращает запрос для получения модели родительского бенефициара
     *
     * @return BaseQuery
     */
    public function parentBeneficiary(): BaseQuery
    {
        return $this->hasOne(Beneficiary::class, 'ID', 'UF_PARENT_ID');
    }
}

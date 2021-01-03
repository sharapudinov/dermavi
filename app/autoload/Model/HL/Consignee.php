<?php

namespace App\Models\HL;

use App\Models\HL\Company\Company;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;

/**
 * Класс-модель, описывающий сущность "Грузополучатель"
 * Class Consignee
 *
 * @package App\Models\HL
 *
 * @property-read PassportData passportData
 * @property-read UserPersonType personType
 * @property-read PublicOfficial publicOfficial
 * @property-read Company company
 */
class Consignee extends D7Model
{
    /** @var string - Символьный код таблицы */
    public const TABLE_CODE = 'consignee';

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
     * Возвращает имя
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this['UF_NAME'];
    }

    /**
     * Возвращает фамилию
     *
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this['UF_SURNAME'];
    }

    /**
     * Возвращает отчество
     *
     * @return string|null
     */
    public function getMiddleName(): ?string
    {
        return $this['UF_MIDDLE_NAME'];
    }

    /**
     * Получает полное имя грузополучателя
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
     * Получает полное имя подписанта c отчеством
     *
     * @return null|string
     */
    public function getFIO(): ?string
    {
        return $this->getSurname()
            . ' ' . $this->getName()
            . ($this->getMiddleName() ? ' ' . $this->getMiddleName() : '');
    }
    /**
     * Возвращает телефон
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this['UF_PHONE'];
    }

    /**
     * Возвращает email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this['UF_EMAIL'];
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
     * Возвращает crm id
     *
     * @return string|null
     */
    public function getCrmId(): ?string
    {
        return $this['UF_CRM_ID'];
    }

    /**
     * Возвращает запрос на получение модели "Тип лица"
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
     * Возвращает запрос для получения модели компании грузополучателя
     *
     * @return BaseQuery
     */
    public function company(): BaseQuery
    {
        return $this->hasOne(Company::class, 'ID', 'UF_COMPANY_ID');
    }
}

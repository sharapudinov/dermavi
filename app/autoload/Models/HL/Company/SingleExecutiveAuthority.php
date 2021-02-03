<?php

namespace App\Models\HL\Company;

use App\Models\HL\PassportData;
use App\Models\HL\PublicOfficial;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;

/**
 * Класс-модель, описывающий сущность "Единоличный исполнительный орган"
 * Class SingleExecutiveAuthority
 *
 * @package App\Models\HL\Company
 *
 * @property-read PassportData passportData
 * @property-read PublicOfficial publicOfficial
 */
class SingleExecutiveAuthority extends D7Model
{
    /** @var string Символьный код таблицы */
    public const TABLE_CODE = 'single_executive_authority';

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
     * Получает полное имя ЕИО
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
     * Получает полное имя ЕИО c отчеством
     *
     * @return null|string
     */
    public function getFIO(): ?string
    {
        return $this->getSurname() . ' ' . $this->getName() . ($this->getMiddleName() ? ' ' . $this->getMiddleName() : '');
    }

    /**
     * Возвращает документы
     *
     * @return string|null
     */
    public function getDocument(): ?string
    {
        return $this['UF_DOCUMENTS'];
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
     * Возвращает запрос на получение модели паспортных данных
     *
     * @return BaseQuery
     */
    public function passportData(): BaseQuery
    {
        return $this->hasOne(PassportData::class, 'ID', 'UF_PASSPORT_DATA_ID');
    }

    /**
     * Возвращает запрос на получение модели публичного должностного лица
     *
     * @return BaseQuery
     */
    public function publicOfficial(): BaseQuery
    {
        return $this->hasOne(PublicOfficial::class, 'ID', 'UF_PUBLIC_OFFICIAL');
    }
}

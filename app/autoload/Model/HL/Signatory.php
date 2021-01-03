<?php

namespace App\Models\HL;

use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;

/**
 * Класс-модель, описывающий сущность "Подписант"
 * Class Signatory
 *
 * @package App\Models\HL
 *
 * @property-read PassportData $passportData
 */
class Signatory extends D7Model
{
    /** @var string - Символьный код таблицы */
    public const TABLE_CODE = 'signatory';

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
     * Возвращает фамилию
     *
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this['UF_SURNAME'];
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
     * Возвращает отчество
     *
     * @return string|null
     */
    public function getMiddleName(): ?string
    {
        return $this['UF_MIDDLE_NAME'];
    }

    /**
     * Получает полное имя подписанта
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
     * Возвращает ИНН
     *
     * @return string|null
     */
    public function getTaxNumber(): ?string
    {
        return $this['UF_TAX_NUMBER'];
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
     * Возвращает запрос для получения модели паспортных данных
     *
     * @return BaseQuery
     */
    public function passportData(): BaseQuery
    {
        return $this->hasOne(PassportData::class, 'ID', 'UF_PASSPORT_DATA_ID');
    }
}

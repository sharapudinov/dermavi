<?php

namespace App\Models\HL;

use Arrilot\BitrixModels\Models\D7Model;

/**
 * Класс-модель, описывающий сущность "Публичное должностное лицо"
 * Class PublicOfficial
 *
 * @package App\Models\HL
 */
class PublicOfficial extends D7Model
{
    /** @var string - Символьный код таблицы */
    const TABLE_CODE = 'public_official';

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
     * Возвращает полное имя
     *
     * @return string
     */
    public function getFullName(): string
    {
        return $this->getSurname() . ' ' . $this->getName() . ' ' . $this->getSurname();
    }

    /**
     * Возвращает степень родства
     *
     * @return string|null
     */
    public function getRelationDegree(): ?string
    {
        return $this['UF_RELATIVE_DEGREE'];
    }

    /**
     * Возвращает должность
     *
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this['UF_POSITION'];
    }
}

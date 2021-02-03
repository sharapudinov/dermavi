<?php

namespace App\Models\HL;

use Arrilot\BitrixModels\Models\D7Model;

/**
 * Класс-модель для сущности "Адрес регистрации"
 * Class RegistrationAddress
 * @package App\Models\HL
 */
class RegistrationAddress extends D7Model
{
    /** @var string - Символьный код таблицы */
    const TABLE_CODE = 'app_registration_address';

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
     * Возвращает идентификатор адреса регистрации
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Возвращает индекс
     *
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this['UF_ZIP_CODE'];
    }

    /**
     * Возвращает идентификатор страны
     *
     * @return string|null
     */
    public function getCountryId(): ?string
    {
        return $this['UF_COUNTRY_ID'];
    }

    /**
     * Возвращает адрес
     *
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this['UF_ADDRESS'];
    }
}

<?php

namespace App\Models\HL;

use Arrilot\BitrixModels\Models\D7Model;

/**
 * Класс-модель для формы "Напишите нам"
 * Class GetInTouchForm
 * @package App\Models\HL
 */
class GetInTouchForm extends D7Model
{
    /** @var string - Символьный код таблицы */
    const TABLE_CODE = 'app_get_in_touch';

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
     * Получаем тему заявки
     *
     * @return string|null
     */
    public function getTheme(): ?string
    {
        return $this['UF_THEME'];
    }

    /**
     * Получаем название компании
     *
     * @return null|string
     */
    public function getCompanyName(): ?string
    {
        return $this['UF_COMPANY_NAME'];
    }

    /**
     * Получаем email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this['UF_EMAIL'];
    }

    /**
     * Получаем имя
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this['UF_NAME'];
    }

    /**
     * Получаем фамилию
     *
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this['UF_SURNAME'];
    }

    /**
     * Получаем вопрос
     *
     * @return string|null
     */
    public function getQuestion(): ?string
    {
        return $this['UF_QUESTION'];
    }

    /**
     * Получаем телефон
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this['UF_PHONE'];
    }
}

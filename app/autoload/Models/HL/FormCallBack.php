<?php


namespace App\Models\HL;

use Arrilot\BitrixModels\Models\D7Model;

/**
 * Класс-модель для формы обратной связи
 * Class GetInTouchForm
 * @package App\Models\HL
 */
class FormCallBack extends D7Model
{
    /** @var string - Символьный код таблицы */
    const TABLE_CODE = 'app_form_callback_header';

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
        return $this['UF_USER_THEME'];
    }

    /**
     * Получаем email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this['UF_USER_EMAIL'];
    }

    /**
     * Получаем имя
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this['UF_USER_NAME'];
    }

    /**
     * Получаем фамилию
     *
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this['UF_USER_SURNAME'];
    }

    /**
     * Получаем вопрос
     *
     * @return string|null
     */
    public function getQuestion(): ?string
    {
        return $this['UF_USER_QUESTION'];
    }
}

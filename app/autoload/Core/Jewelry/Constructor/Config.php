<?php

namespace App\Core\Jewelry\Constructor;

class Config
{
    /** @var string Имя корневой директории конструктора */
    public const BASE_DIR = 'create-jewelry';

    /**
     * Ограничение на количество камней для подбора комплектов в генераторе.
     *
     * @return int
     */
    public static function getDiamondsLimit(): int
    {
        return env('CONSTRUCTOR_DIAMONDS_LIMIT', 0);
    }

    /**
     * Количество комбинаций по каждой оправе, после набора которого мы перестаём переходить к следующей карте подбора.
     *
     * @return int
     */
    public static function getBlankDiamondsLimit(): int
    {
        return env('CONSTRUCTOR_BLANK_DIAMONDS_LIMIT', 0);
    }

    /**
     * Массив артикулов трилогий.
     *
     * @return array|string[]
     */
    public static function getTrilogyCodes(): array
    {
        return env('CONSTRUCTOR_TRILOGY_CODES', ['ЗБ54К3']);
    }
}

<?php

namespace App\Core\Jewelry\Constructor\Blank;

use App\Core\Jewelry\Constructor\Config;

/**
 * Класс для определения относится ли оправа к трилогиям
 */
class TrilogyDetector
{
    /**
     * Определяет относится ли оправа к трилогиям.
     *
     * @param string|null $name
     * @param string|null $code
     *
     * @return bool
     */
    public static function isTrilogy(?string $name, ?string $code): bool
    {
        return self::isTrilogyByName($name) || self::isTrilogyByCode($code);
    }

    /**
     * Определяет содержит ли переданное название слово "трилогия".
     *
     * @param string|null $name
     *
     * @return bool
     */
    public static function isTrilogyByName(?string $name): bool
    {
        return $name !== null && stripos($name, 'трилогия') !== false;
    }

    /**
     * Определяет относится ли переданный артикул к трилогиям
     *
     * @param string|null $code
     *
     * @return bool
     */
    public static function isTrilogyByCode(?string $code): bool
    {
        return $code !== null && in_array($code, Config::getTrilogyCodes(), true);
    }
}

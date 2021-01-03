<?php

namespace App\Helpers;

/**
 * Класс-хелпер для работы с числами
 * Class NumberHelper
 * @package App\Helpers
 */
class NumberHelper
{
    /**
     * Приводим цену к нужному виду
     *
     * @param float $price - Цена
     * @return string
     */
    public static function transformNumberToPrice(float $price): string
    {
        return number_format(ceil($price), 0, '.', ' ');
    }

    /**
     * Приводим цену к виду с 2-мя знаками после запятой
     *
     * @param float|int $price - Цена
     * @return string
     */
    public static function transformNumberToPriceTwo($price): string
    {
        if((is_numeric($price) && (intval($price) == floatval($price)) && intval($price) > 0)) {
            $decimals = 0;
        } else {
            $decimals = 2;
        }

        return number_format($price, $decimals, '.', ' ');
    }

    /**
     * Преобразует вес в нужный вид
     *
     * @param float|null $weight Вес
     *
     * @return string
     */
    public static function transformWeight(?float $weight, string $decPoint = '.'): string
    {
        return number_format(round($weight, 2), 2, $decPoint, '');
    }

    /**
     * Возвращает число, в которое добавлено разделение разрядов пробелами (100 000)
     *
     * @param string $number - Число
     *
     * @return string
     */
    public static function addThousandsSeparatorToNumber(string $number): string
    {
        return number_format($number, 0 ,'', ' ');
    }

    /**
     * Приводим число к целым тысячам
     *
     * @param int $number
     * @return int
     */
    public static function transformNumberToThousands(int $number): int
    {
        return (int) $number / 1000;
    }
}

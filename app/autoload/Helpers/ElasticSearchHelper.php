<?php

namespace App\Helpers;

/**
 * Класс-хелпер для работы с Эластиком
 * Class ElasticSearchHelper
 * @package App\Helpers
 */
class ElasticSearchHelper
{
    /**
     *Заменяет зарезервированные символы на \\символ
     * @param string $string
     * @return string
     */
    public static function escapeElasticReservedChars(string $string): string
    {
        $regex = "/[\\+\\-\\=\\&\\|\\!\\(\\)\\{\\}\\[\\]\\^\\\"\\~\\*\\<\\>\\?\\:\\\\\\/]/";
        return preg_replace($regex, addslashes('\\$0'), $string);
    }

    /**
     * Заменяет нулевое значение на null
     *
     * @param $value
     *
     * @return mixed
     */
    public static function zeroToNull($value)
    {
        if ($value && $value > 0) {
            return $value;
        }

        return null;
    }
}

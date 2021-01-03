<?php

namespace App\Helpers;

/**
 * Класс-хелпер для работы с временем жизн кеша
 * Class TTL
 * @package App\Helpers
 */
class TTL
{
    /**
     * @var int минута (в минутах)
     */
    const MINUTE = 1;
    /**
     * @var int час (в минутах)
     */
    const HOUR = 60;
    /**
     * @var int день (в минутах)
     */
    const DAY = 1440;
    /**
     * @var int неделя (в минутах)
     */
    const WEEK = 10080;
    /**
     * @var int месяц (30 дней, в минутах)
     */
    const MONTH = 43200;
    /**
     * @var int год (365 дней, в минутах)
     */
    const YEAR = 525600;

    /**
     * Переводит количество секунд в минуты.
     * @param mixed $seconds
     * @return int
     */
    public static function sec2min($seconds): int
    {
        return ((int) $seconds) / 60;
    }

    /**
     * Переводит секунды в милисекунды
     *
     * @param int $seconds Секунды
     *
     * @return int
     */
    public static function sec2Millisec(int $seconds): int
    {
        return $seconds * 1000;
    }
}

<?php

namespace App\Helpers;

/**,
 * Class MathHelper
 *
 * @package App\Helpers
 */
class MathHelper
{
    /**
     * Факториал
     * @param string $number
     *
     * @return string
     */
    public static function fact(string $number): string
    {
        $result = 1;
        for ($i = 1; $i <= $number; $i++) {
            $result *= $i;
        }

        return $result;
    }

    /**
     * Число сочетаний $n по $k
     *
     * @param string $string
     * @return string
     */
    public static function combinationCount($n,$k): string
    {
        return (self::fact($n) / (self::fact($n - $k) * self::fact($k)));
    }
}

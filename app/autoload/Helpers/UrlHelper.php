<?php

namespace App\Helpers;

/**
 * Class UrlHelper
 *
 * @package App\Helpers
 */
class UrlHelper
{

    /**
     * Parse a query string into an associative array.
     * If multiple values are found for the same key, the value of that key
     * value pair will become an array. This function does not parse nested
     * PHP style arrays into an associative array (e.g., foo[a]=1&foo[b]=2 will
     * be parsed into ['foo[a]' => '1', 'foo[b]' => '2']).
     *
     * @param string   $str         Query string to parse
     * @param int|bool $urlEncoding How the query string is encoded
     *
     * @return array
     */
    public static function parse_query($str, $urlEncoding = true)
    {
        $result = [];

        if ($str === '') {
            return $result;
        }

        if ($urlEncoding === true) {
            $decoder = function ($value) {
                return rawurldecode(str_replace('+', ' ', $value));
            };
        } elseif ($urlEncoding === PHP_QUERY_RFC3986) {
            $decoder = 'rawurldecode';
        } elseif ($urlEncoding === PHP_QUERY_RFC1738) {
            $decoder = 'urldecode';
        } else {
            $decoder = function ($str) {
                return $str;
            };
        }

        foreach (explode('&', $str) as $kvp) {
            $parts = explode('=', $kvp, 2);
            $key   = $decoder($parts[0]);
            $value = isset($parts[1]) ? $decoder($parts[1]) : null;
            if (!isset($result[$key])) {
                $result[$key] = $value;
            } else {
                if (!is_array($result[$key])) {
                    $result[$key] = [$result[$key]];
                }
                $result[$key][] = $value;
            }
        }

        return $result;
    }

    /**
     * Проверяет находится ли пользователь на определенной странице
     *
     * @param string $url Ссылка
     *
     * @return bool
     */
    public static function isPage(string $url): bool
    {
        $currentPageUrl = rtrim(str_replace(['/en/', '/cn/'], '/', $_SERVER['PHP_SELF']), '/');
        $url            = rtrim($url, '/');

        return $currentPageUrl == $url;
    }

    /**
     * Получает "чистый" путь без языковых констант
     *
     * @return string
     */
    public static function getPage()
    {
        $currentPageUrl = rtrim(str_replace(['/en/', '/cn/'], '/', $_SERVER['PHP_SELF']), '/');

        return str_replace(['index.php'], '', $currentPageUrl);
    }

    public static function isHasPage(string $url)
    {
        return strpos(self::getPage(), $url) !== false;
    }
}

<?php

namespace App\RequestHooks\OnPageStart;

use CSite;

/**
 * Данный хук добавляет проверку заголовков
 * HTTP_ORIGIN и HTTP_REFERER для всех POST и т д запросов для защиты от CSRF атак.
 */
class AntiCSRF
{
    /**
     * Данный метод - единственный метод класса, который предполгается менять при необходимости.
     * В нём задаются дополнительные условия при которых не проводится проверка заголовков.
     * Например, можно выключить проверку в директории /api/ при помощи "return CSite::inDir('/api/')"
     *
     * @return bool
     */
    public static function skipCheck()
    {
        return true;
    }

    /**
     * Основной обработчик хука.
     */
    public static function handle()
    {
        if (self::skipCheck()) {
            return;
        }

        // Пропускаем проверку для консольного приложения и безопасных HTTP методов.
        if (empty($_SERVER['REQUEST_METHOD']) || in_array(strtoupper($_SERVER['REQUEST_METHOD']), ['GET', 'HEAD', 'OPTIONS'])) {
            return;
        }

        $origin = self::getOrigin();
        if ($origin) {
            if ($origin !== $_SERVER['SERVER_NAME']) {
                self::terminateRequest();
            }
        } else {
            $referer = self::getReferer();
            if ($referer && ($referer !== $_SERVER['SERVER_NAME'])) {
                self::terminateRequest();
            }
        }
    }

    /**
     * Получение заголовка Origin в виде готовом для сравнения с SERVER_NAME.
     *
     * @return string
     */
    private static function getOrigin()
    {
        if (empty($_SERVER['HTTP_ORIGIN'])) {
            return '';
        }

        return self::stripProtocols($_SERVER['HTTP_ORIGIN']);
    }

    /**
     * Получение заголовка Referer в виде готовом для сравнения с SERVER_NAME.
     *
     * @return string
     */
    private static function getReferer()
    {
        if (empty($_SERVER['HTTP_REFERER'])) {
            return '';
        }

        return strtok(self::stripProtocols($_SERVER['HTTP_REFERER']), '/');
    }

    /**
     * Завершение запроса с ошибкой.
     */
    private static function terminateRequest()
    {
        http_response_code(400);
        die('Request origin looks suspicious');
    }

    /**
     * Удаление из урла протоколов.
     *
     * @param string $url
     * @return string
     */
    private static function stripProtocols($url)
    {
        return str_replace(['https://www.', 'http://www.', 'https://', 'http://'], '', $url);
    }
}

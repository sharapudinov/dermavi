<?php

namespace App\Helpers;

/**
 * Класс-хелпер для работы с серверными переменными
 * Class ServerHelper
 * @package App\Helpers
 */
class ServerHelper
{
    /**
     * Получает протокол сервера
     *
     * @return string
     */
    public static function getProtocol(): string
    {
        $protocol = null;
        if (in_console()) {
            $protocol = config('server')['protocol'];
        } else {
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                $protocol = 'https';
            } else {
                $protocol = 'http';
            }
        }
        
        return $protocol;
    }
    
    /**
     * Получает адрес сервера
     *
     * @return string
     */
    public static function getHttpHost(): string
    {
        $httpHost = null;
        if (in_console()) {
            $httpHost = config('server')['http_host'];
        } else {
            $httpHost = $_SERVER['HTTP_HOST'];
        }
        
        return $httpHost;
    }
}

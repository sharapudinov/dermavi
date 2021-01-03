<?php

namespace App\RequestHooks\OnBeforeProlog;

class FrontendSwitch
{
    public static function handle()
    {
        $key = frontend()->cookieName;
        if (in_production() || !isset($_GET[$key])) {
            return;
        }

        if ($_GET[$key] === 'dev') {
            setcookie($key, 'dev', time() + 60 * 60 * 24); // включаем на сутки
            $_COOKIE[$key] = 'dev';
        } else {
            setcookie($key, "", time() - 3600); // удаляем
            unset($_COOKIE[$key]);
        }
    }
}
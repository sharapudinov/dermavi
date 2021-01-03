<?php

namespace App\RequestHooks\OnPageStart;

class ApplySecurityHeaders
{
    public static function handle()
    {
        header('X-XSS-Protection: 1; mode=block');
//        header('X-Frame-Options: SAMEORIGIN'); вызывает проблемы для Яндекс-метрики и не только
        header('X-Content-Type-Options:nosniff');

        //header_remove("X-Powered-CMS");
        //header_remove("X-Powered-By");
    }
}

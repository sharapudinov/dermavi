<?php

namespace App\RequestHooks\OnBeforeProlog;

class FrontendClearCache
{
    public static function handle()
    {
        global $USER;

        if (!isset($_GET['clear_frontend_cache']) && $_GET['clear_frontend_cache'] !== 'Y') {
            return;
        }

        if (!$USER->IsAdmin()) {
            return;
        }

        frontend()->updateVersion();
    }
}

<?php

namespace App\RequestHooks\OnBeforeProlog;

/**
 * Тюнинг транзакций для New Relic.
 */
class NewRelicTuning
{
    public static function handle()
    {
        if (!extension_loaded('newrelic')) {
            return;
        }

        // На тестовом сервере замеряем только транзакции c площадки "master.project.greensight.ru" и консольные скрипты
        if (!in_production() && $_SERVER['SERVER_NAME'] && strtok($_SERVER['SERVER_NAME'], '.') !== 'master') {
            newrelic_ignore_transaction();
            newrelic_ignore_apdex();

            return;
        }

        // явно указываем Ньюрелику что все что запущено в консоли должно определять как non-web
        // а всё что не в консоли как web
        newrelic_background_job(in_console());

        // детализация названия команд которые запускаются через bxcli
        if (in_console() && (string) $_SERVER['argv'][0] === 'bxcli') {
            $command = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';
            newrelic_name_transaction("bxcli $command");
        }

        // детализация названий транзакций которые заворачиваются веб-сервером на urlrewrite.php
        if (!empty($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['REAL_FILE_PATH'])) {
            if (mb_substr($_SERVER['SCRIPT_FILENAME'], -14) === 'urlrewrite.php') {
                newrelic_name_transaction($_SERVER['REAL_FILE_PATH']);
            }
        }

        // Для медленных транзакций сохраним ID юзера (или 0 если пользователь неавторизован)
        // Можно добавить сюда другие параметры, специфичные для проекта
        newrelic_add_custom_parameter('user_id', (int) $GLOBALS['USER']->GetId());
    }
}

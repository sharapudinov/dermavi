<?php

use NunoMaduro\Collision\Provider as CollisionProvider;

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT & ~E_USER_WARNING & ~E_USER_NOTICE & ~E_COMPILE_WARNING & ~E_DEPRECATED);

// Директория logs должна быть доступна для записи
ini_set('error_log', logs_path('php_errors.log'));
ini_set('log_errors', 1);

if (env('APP_DEBUG') && env('APP_ENV', 'production') !== 'production') {
    ini_set('display_errors', 1);

    // Используем дополнительные обработчики ошибок только если они есть.
    if (class_exists(Whoops\Util\Misc::class)) {
        if (Whoops\Util\Misc::isCommandLine() && class_exists(CollisionProvider::class)) {
            // Для консоли - Collision, основанный на Whoops
            (new CollisionProvider())->register();
        } else {
            // Для HTTP, или если Collision не подключен - просто Whoops.
            $whoops = new Whoops\Run;

            // Показ ошибок на экране
            $handler = Whoops\Util\Misc::isCommandLine() ? new Whoops\Handler\PlainTextHandler : new Whoops\Handler\PrettyPageHandler;
            $whoops->pushHandler($handler);

            // Логирование ошибок вручную
            $whoops->pushHandler(function ($exception) {
                $message = $exception->getMessage();
                $trace = $exception->getTraceAsString();
                $file = $exception->getFile();
                $line = $exception->getLine();
                error_log("$message in file \"$file\" on line $line, trace: $trace");
            });

            $whoops->register();
        }
    }
} else {
    // Не показываем ошибки (боевой)
    ini_set('display_errors', 0);
}

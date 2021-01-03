<?php

/*
|--------------------------------------------------------------------------------------
|  Task File
|--------------------------------------------------------------------------------------
|
| Этот файл - аналог crontab. В нём регистрируются все задания, а в кронтаб добавляется
| лишь одна строка.
| Подробности: https://github.com/lavary/crunz#scheduling-frequency-and-constraints
|
 */

use Crunz\Schedule;

$projectRoot = __DIR__ . '/../../';
$scheduler = new Schedule();

$scheduler->run('php -f app/cron/bitrix_cron_events.php')
    ->in($projectRoot)
    ->everyMinute()
    ->description('Bitrix agents');

/** System check */
//$scheduler->run('php bxcli system:check full')
//    ->in($projectRoot)
//    ->cron('0 3 * * *')
//    ->description('Runs bitrix system check');

/** Запуск конвертации изображений в формат WEBP */
//$scheduler->run('php bxcli image:convert_to_webp')
//          ->in($projectRoot)
//          ->cron('0 5 * * *')
//          ->description('php bxcli image:convert_to_webp');


//Генерация sitemap
/*$scheduler->run('php bxcli sitemap:generation')
          ->in($projectRoot)
          ->cron('0 13 * * *')
          ->description('Sitemap generation');*/



return $scheduler;

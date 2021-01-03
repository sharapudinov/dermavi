<?php

use Bitrix\Main\Config\Option;

define(
    'VERSION',
    in_array(env('APP_ENV', 'production'), ['production', 'stage'], true)
        ? (int)Option::get('main', 'app_version', 1)
        : time()
);

// ------- Путь до файлов импорта ---------
define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'].'/upload/');

// ------- Путь до спрайта SVG ---------
define('SPRITE_SVG', '/assets/build/img/svg/sprite.svg');

// Здесь не должно быть констант для ID инфоблоков.
// ID инфоблока всегда получается по его коду при помощи хэлпера `iblock_id($code)`
// Он запрашивает данные из базы/кэша только при первом обращении.
// Подробности тут https://github.com/arrilot/bitrix-iblock-helper/

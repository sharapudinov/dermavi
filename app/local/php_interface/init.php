<?php

ini_set('memory_limit','1024M');
/**
 * init.php за *крайне* редким исключением трогать не надо.
 * Если необходимо выполнить какое-либо действие в начале каждой страницы, то следует
 * зарегистрировать хук в request_hooks.php в стэк OnPageStart или OnBeforeProlog.
 */

use App\Components\ExtendedComponent;
use Bitrix\Main\Loader;

Loader::includeModule('iblock');
Arrilot\BitrixBlade\BladeProvider::register(app_path('views/blade'));
Arrilot\BitrixModels\ServiceProvider::register();
Arrilot\BitrixHLBlockFieldsFixer\ServiceProvider::register();
Arrilot\BitrixModels\ServiceProvider::registerEloquent();
MsNatali\BitrixDebug\DebugVar::register();

define('VUEJS_DEBUG', true);

require "error_reporting.php";
require "constants.php";
require "container.php";
require "helpers.php";
require "loggers.php";
require "request_hooks.php";
require "handlers.php";
require "migrations.php";
require "blade.php";

if (PHP_VERSION_ID >= 70200) {
    new ExtendedComponent(); // @FIXME затычка для ошибки определения класса компонента в битриксе
}

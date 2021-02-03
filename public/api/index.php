<?php

use Arrilot\SlimApiController\OnlyArgsStrategy;

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("STOP_STATISTICS", true);
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);

require $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";

$app = require app_path('api.php');

$container = $app->getContainer();
$container['logger'] = function () {
    return logger('api');
};
$container['foundHandler'] = function () {
    return new OnlyArgsStrategy();
};

$app->run();

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');

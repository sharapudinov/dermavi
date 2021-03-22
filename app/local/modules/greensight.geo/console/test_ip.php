<?

use Bitrix\Main\Loader;

$_SERVER['DOCUMENT_ROOT'] = realpath(dirname(__FILE__) . '/../../../..');

define('NO_AGENT_CHECK', true);
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('BX_CRONTAB', true);
define('BX_NO_ACCELERATOR_RESET', true);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

Loader::includeModule("greensight.geo");

$ip = $argv[1];
if (!$ip) {
    die('Необходимо передать IP в качестве параметра');
}

$info = \Greensight\Geo\Location::getInstance()->getInfoFromDatabase($ip);
var_dump($info);
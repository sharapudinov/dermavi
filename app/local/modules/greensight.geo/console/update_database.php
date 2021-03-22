<?

use Bitrix\Main\Loader;
use Greensight\Geo\Location;
use Greensight\Geo\Providers\ProviderInterface;

$_SERVER['DOCUMENT_ROOT'] = realpath(dirname(__FILE__) . '/../../../..');

define('NO_AGENT_CHECK', true);
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('BX_CRONTAB', true);
define('BX_NO_ACCELERATOR_RESET', true);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

Loader::includeModule("greensight.geo");

$provider = Location::getInstance()->getProvider();

if (isset($argv[1])) {
    $className = $argv[1];
    if (!class_exists($className)) {
        echo "Class $className does not exist. Abort. ".PHP_EOL;
        die();
    }

    $provider = new $className();
    if (!($provider instanceof ProviderInterface)) {
        echo "Class $className does not implement Greensight\Geo\Providers\ProviderInterface. Abort. ".PHP_EOL;
        die();
    }
}

$provider->updateDatabase();

echo "Database for provider \"" . get_class($provider) . '" has been successfully updated' . PHP_EOL;
<?php

namespace App\RequestHooks\OnBeforeProlog;

use App\Core\SiteVersion\LanguageVersion;
use App\Helpers\LanguageHelper;
use Bitrix\Main\Context;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

/**
 * Класс для определения языковой версии сайта при ее загрузке
 * Class SiteVersion
 * @package App\RequestHooks\OnBeforeProlog
 */
class SiteVersion
{
    /**
     * Запускает хуку
     *
     * @return void
     */
    public static function handle(): void
    {
        if (
            !empty($_REQUEST['DISALLOW_REDIRECT'])
            || ($_GET['variant'] ?? '') === 'b2b-require-auth'
            || is_api()
            || in_console()
            || strpos($_SERVER['REQUEST_URI'], 'bitrix') !== false
            || (new CrawlerDetect)->isCrawler()
            || strpos(Context::getCurrent()->getRequest()->getUserAgent(), 'googleweblight') !== false
            || is_directory('/rest/')
        ) {
            return;
        }

        /** @var string $switchToVersion - Языковая версия сайта */
        $switchToVersion = (new LanguageVersion())->getUserLanguageVersion();

        if ($switchToVersion && $switchToVersion !== LanguageHelper::getLanguageVersion()) {
            if (is404()) {
                header('HTTP/1.1 301 Moved Permanently');
            }

            LocalRedirect(generate_link_by_language_version($switchToVersion));
        }
    }
}

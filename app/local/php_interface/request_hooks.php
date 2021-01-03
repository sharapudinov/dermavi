<?php

/**
 * В данном файле регистрируюся хуки для жизненного цикла запроса.
 * Каждый хук должен представлять из себя отдельный класс расположенный в app/autoload/RequestHooks/
 * В классе должен присутствовать статический метод handle() который и будет запущен.
 */

use App\RequestHooks as Hooks;
use Bitrix\Main\EventManager;

$stacks = [
//    // В начале выполняемой части пролога сайта, после init.php и старта сессии
//    'OnPageStart' => [
//        Hooks\OnPageStart\AntiCSRF::class,
//        Hooks\OnPageStart\ApplySecurityHeaders::class,
//        Hooks\OnPageStart\ConfigureRecaptcha::class
//    ],
//
//    // В выполняемой части пролога, после определения шаблона сайта (SITE_TEMPLATE_ID) и инициализации $USER
//    'OnBeforeProlog' => [
//        Hooks\OnBeforeProlog\RestrictAccessToAdminSections::class,
//        Hooks\OnBeforeProlog\NewRelicTuning::class,
//        Hooks\OnBeforeProlog\FrontendSwitch::class,
//        Hooks\OnBeforeProlog\FrontendClearCache::class,
//        Hooks\OnBeforeProlog\SiteVersion::class,
//        Hooks\OnPageStart\ClientPBHook::class
//    ],
//
//    // В начале визуальной части пролога. К этому моменту пройдена проверка прав доступа и начата буферизация вывода
//    'OnProlog' => [],
//
//    // В конце визуальной части эпилога, после footer.php
//    'OnEpilog' => [],
//
//    // В конце выполняемой части эпилога, буферизация завершена (наверное...)
//    'OnAfterEpilog' => [],
];

$eventManager = EventManager::getInstance();
foreach ($stacks as $event => $hooks) {
    foreach ((array) $hooks as $hook) {
        $eventManager->addEventHandler('main', $event, [$hook, "handle"]);
    }
}

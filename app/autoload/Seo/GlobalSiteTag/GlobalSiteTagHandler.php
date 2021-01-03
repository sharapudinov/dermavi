<?php

namespace App\Seo\GlobalSiteTag;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use RuntimeException;
use function logException;
use function sprint_options_get;

/**
 * Класс для добавления в footer скрипта, отправляющего событие gtag
 * Class GlobalSiteTagHandler
 * @package App\Seo\GlobalSiteTag
 */
class GlobalSiteTagHandler
{
    public const GTAG_EVENTS = 'gtag_events';

    /**
     * Добавляет отправку события в footer
     *
     * @param GlobalSiteTagEvent $event
     */
    public function addEvent(GlobalSiteTagEvent $event): void
    {
        try {
            app()->AddViewContent(static::GTAG_EVENTS, $this->createScript($event));
        } catch (LoaderException | RuntimeException $e) {
            logException($e);
        }
    }

    /**
     * Создаёт html-код скрипта
     *
     * @param GlobalSiteTagEvent $event
     * @return string
     * @throws LoaderException
     */
    private function createScript(GlobalSiteTagEvent $event): string
    {
        return sprintf(
            <<<SCRIPT
<!--suppress JSUnresolvedVariable, JSUnresolvedFunction -->
<script>
    if (typeof gtag === 'function') {
        gtag('event', '%s', {'send_to': '%s', 'value': '%s', 'items': %s});
    } else {
        console.error('gtag is not function');
    }
</script>
SCRIPT,
            $event->getName(),
            $this->getId(),
            $event->getValue(),
            json_encode($event->getItems())
        );
    }

    /**
     * Возвращает идентификатор Gtag
     *
     * @return string
     * @throws RuntimeException
     *
     * @throws LoaderException
     */
    private function getId(): string
    {
        if (!Loader::includeModule('sprint.options')) {
            throw new LoaderException('Unable to load module sprint.options');
        }

        $id = sprint_options_get('GLOBAL_SITE_TAG_ID');

        if (!$id) {
            throw new RuntimeException('Empty GLOBAL_SITE_TAG_ID');
        }

        if (!is_string($id)) {
            throw new RuntimeException('GLOBAL_SITE_TAG_ID is not string');
        }

        return $id;
    }
}

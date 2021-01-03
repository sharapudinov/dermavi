<?php
namespace App\EventHandlers;

use Arrilot\BitrixIblockHelper\HLblock;
use Bitrix\Main\Application;
use Bitrix\Main\ORM\Event;
use Bitrix\Main\EventManager;
use Bitrix\Main\SystemException;

/**
 * Обработчики очистки кэша для разных событий.
 * Class CacheHandlers
 * @package App\EventHandlers
 */
class CacheHandlers
{
    /**
     * Регистрирует обработчики для очистки тэгированного кэша для HL-блоков.
     * @param EventManager $em
     */
    public static function register(EventManager $em): void
    {
        $handler = [self::class, 'clearHL'];
        $events = ['OnAfterUpdate', 'OnAfterDelete', 'OnAfterAdd'];
        $blocks = HLblock::getAllByTableNames();
        
        foreach ($blocks as $block) {
            foreach ($events as $event) {
                $em->addEventHandler('', $block['NAME'].$event, $handler);
            }
        }
    }
    
    /**
     * Сбрасывает тэгированный кэш для HL-блока.
     * @param Event $event
     * @throws SystemException
     */
    public static function clearHL($event): void
    {
        //Application::getInstance()->getTaggedCache()->clearByTag($event->getEntity()->getDBTableName());
    }
}

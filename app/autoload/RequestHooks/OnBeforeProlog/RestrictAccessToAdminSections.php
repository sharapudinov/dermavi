<?php

namespace App\RequestHooks\OnBeforeProlog;

use CSite;

/**
 * Класс для ограничение доступа к административным разделам по группе/ip.
 * Крайне полезен если не установлен модуль проактивной защиты или как-то некорректно настроены права в админке.
 */
class RestrictAccessToAdminSections
{
    /**
     * Список разделов которые трактуем как "административные" и куда по полной ограничиваем доступ.
     *
     * @return array
     */
    private static function sectionsToProtect()
    {
        return ['/bitrix/admin'];
    }

    /**
     * Список групп которым разрешен доступ в административные разделы.
     * Для этого пользователь должен состоять хотя бы в одной из указанных групп.
     * Если возвращает null, то данная проверка не производится и административные разделы доступны всем группам.
     *
     * @return array|null
     */
    private static function groupsWhitelist()
    {
        return null;
    }

    /**
     * Список IP адресов которым разрешен доступ в административные разделы.
     * Если возвращает null, то данная проверка не производится и административные разделы доступны со всех IP.
     *
     * @return array|null
     */
    private static function ipsWhitelist()
    {
        return null;
    }

    /**
     * Входная точка в класс.
     */
    public static function handle()
    {
        global $USER;

        if (!self::isAdminSection()) {
            return;
        }

        if (!$USER->IsAuthorized()) {
            return;
        }

        if (!self::checkGroup()) {
            header('HTTP/1.0 403 Forbidden');
            die('Access blocked by App\RequestHooks\RestrictAccessToAdminSections::checkGroup()');
        }

        if (!self::checkIp()) {
            header('HTTP/1.0 403 Forbidden');
            die('Access blocked by App\RequestHooks\RestrictAccessToAdminSections::checkIp()');
        }
    }

    /**
     * Считается ли текущий раздел админстративным?
     *
     * @return bool
     */
    private static function isAdminSection()
    {
        $isAdminSection = false;
        foreach (self::sectionsToProtect() as $section) {
            if (CSite::InDir($section)) {
                $isAdminSection = true;
                break;
            }
        }

        return $isAdminSection;
    }
    
    /**
     * Проверка наличия группы пользователя юзера в вайтлисте.
     *
     * @return bool
     */
    private static function checkGroup()
    {
        global $USER;

        $whiteList = self::groupsWhitelist();

        return $whiteList === null ? true : (bool) array_intersect($whiteList, $USER->GetUserGroupArray());
    }

    /**
     * Проверка наличия IP юзера в вайтлисте.
     *
     * @return bool
     */
    private static function checkIp()
    {
        $whiteList = self::ipsWhitelist();
    
        return $whiteList === null ? true : in_array(self::getUserIp(), $whiteList);
    }
    
    /**
     * Получение IP текущео пользователя.
     * При наличии всяких Reverse Proxy (например балансировщик на nginx или Qrator) $_SERVER['REMOTE_ADDR'] будет
     * содержать не IP юзера а IP этой прокси.
     * В этом случае, нужно модифицировать этот метод, чтобы он получал IP из нужного $_SERVER['HTTP_...'] заголовка.
     * Этот HTTP заголовок должен явно задаваться веб-сервером, иначе его очень легко подделать.
     *
     * @return string
     */
    private static function getUserIp()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}

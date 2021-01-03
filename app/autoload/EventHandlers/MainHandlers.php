<?php

namespace App\EventHandlers;

use CUtil;

/**
 * Класс-обработчик для главных событий
 * Class MainHandlers
 * @package App\EventHandlers
 */
class MainHandlers
{

    /**
     * Осуществляем перехват родного события Главного модуля Битрикса "OnChangeFile"
     * Нам нужно предотвратить измнение urlrewrite.php, при измнение свойств страниц через админку.
     * Битрикс просто ломает структуру этого файла.
     * Ниже копия метода \CAllMain::OnChangeFileComponent с комментариями кода меняющего urlrewrite.php
     * @param $path
     * @param $site
     * @see \CAllMain::OnChangeFileComponent
     */
    public static function OnChangeFileComponent($path, $site)
    {
        // kind of optimization
        if(HasScriptExtension($path))
        {
            if($site === false)
            {
                $site = SITE_ID;
            }
            $docRoot = \CSite::GetSiteDocRoot($site);

            //Main\UrlRewriter::delete($site, array("PATH" => $path, "!ID" => ''));
            \Bitrix\Main\Component\ParametersTable::deleteByFilter(array("SITE_ID" => $site, "REAL_PATH" => $path));
            //Main\UrlRewriter::reindexFile($site, $docRoot, $path);
        }
    }

    function addFrontendButtonToTopPanel()
    {
        global $APPLICATION, $USER;
        if (!$USER->IsAdmin()) {
            return;
        }

        $devMode = frontend()->isInDevMode();
        $key = frontend()->cookieName;
        $newBuild = $devMode ? 'production' : 'dev';

        $submenu = [];
        if (!in_production()) {
            $submenu[] = [
                "TEXT"    => 'Включить dev сборку',
                "TITLE"   => '',
                "CHECKED" => $devMode,
                "ACTION"  => "jsUtils.Redirect([], '".CUtil::addslashes($APPLICATION->GetCurPageParam("{$key}={$newBuild}", [$key]))."');",
                "DEFAULT" => false,
                "HK_ID"   => 'top_panel_frontend_switch'
            ];
        }

        if (in_production()) {
            $submenu[] = [
                "TEXT"    => 'Сбросить браузерный кэш',
                "TITLE"   => '',
                "ACTION"  => "jsUtils.Redirect([], '".CUtil::addslashes($APPLICATION->GetCurPageParam("clear_frontend_cache=Y", ['clear_frontend_cache']))."');",
                "DEFAULT" => false,
                "HK_ID"   => 'top_panel_frontend_clear_cache'
            ];
        }

        $APPLICATION->AddPanelButton([
            "ID"        => "frontend",
            "ICON"      => "icon-wizard",
            "TEXT"      => "Фронтэнд",
            "MAIN_SORT" => 1100,
            "SORT"      => 10,
            "MENU"      => $submenu,
            "HINT"      => [
                "TITLE" => "Greensight Frontend",
                "TEXT"  => "Работа с фронтэндом в целом и сборщиком в частности"
            ],
        ]);
    }
}

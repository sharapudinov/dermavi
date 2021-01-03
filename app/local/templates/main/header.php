<?php

use App\Helpers\UserHelper;
use App\Seo\GlobalSiteTag\GlobalSiteTagHandler;
use Bitrix\Main\Page\Asset;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<!DOCTYPE html>
<!--[if IE 8 ]>
<html class="ie8" lang="ru-RU" prefix="og: http://ogp.me/ns#"><![endif]-->
<!--[if IE 9 ]>
<html class="ie9" lang="ru-RU" prefix="og: http://ogp.me/ns#"><![endif]-->
<!--[if gt IE 9]><!-->
<html lang="<?= \App\Helpers\LanguageHelper::getHTMLLang(); ?>" prefix="og: http://ogp.me/ns#">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="skype_toolbar" content="skype_toolbar_parser_compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    $APPLICATION->ShowMeta("keywords");
    $APPLICATION->ShowMeta("description");
    $APPLICATION->ShowMeta("robots");
    ?>
    <title><?php $APPLICATION->ShowTitle(); ?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="preload" href="<?= frontend()->css('external.css'); ?>" as="style">
    <link rel="preload" href="<?= frontend()->css('internal.css'); ?>" as="style">
    <link rel="preload" href="<?= frontend()->js('external.js') ?>" as="script">
    <link rel="preload" href="<?= frontend()->js('internal.js') ?>" as="script">
    <link href="<?= frontend()->css('external.css'); ?>" rel="stylesheet">
    <link href="<?= frontend()->css('internal.css'); ?>" rel="stylesheet">

    <!--[if lte IE 8]>
    <link rel="preload" href="<?= frontend()->css('fonts_ie.css'); ?>" as="style">
    <link rel="stylesheet" href="<?=frontend()->css('fonts_ie.css');?>">
    <![endif]-->


    <script>
        var pageLoader = null;
        var timeout = null;

        timeout = setInterval(function () {
            pageLoader = document.querySelector('.js-page-loader');
            if (pageLoader) {
                clearTimeout(timeout);
                pageLoader.classList.add('isLoad');
            }
        }, 200);

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function () {
                pageLoader.classList.add('isHide');
            }, 1800);
            setTimeout(function () {
                pageLoader.remove();
                $(document).trigger('pageLoaderRemoved');
            }, 3200);
        });
    </script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script>
        // IE 9+
        var ready = function (fn) {
            if (typeof fn !== 'function') return;
            if (document.readyState === 'complete') return fn();
            document.addEventListener('DOMContentLoaded', fn, false);
        };
    </script>
    <!-- Для ie10 -->
    <!--[if !IE]><!-->
    <script>if (/*@cc_on!@*/false) {
            document.documentElement.className += ' ie10';
        }</script>
    <!--<![endif]-->

    <?php $APPLICATION->ShowHeadStrings(); ?>
    <?php $APPLICATION->ShowHeadScripts(); ?>
</head>
<body>

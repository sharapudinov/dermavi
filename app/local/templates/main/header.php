<?php

use Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\Extension;

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
    <title><?php
        $APPLICATION->ShowTitle(); ?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="preload" href="<?= frontend()->css('external.css'); ?>" as="style">
    <link rel="preload" href="<?= frontend()->css('internal.css'); ?>" as="style">
    <link rel="preload" href="<?= frontend()->js('slick/slick/slick.css'); ?>" as="style">
    <link rel="preload" href="<?= frontend()->js('internal.js') ?>" as="script">
    <link href="<?= frontend()->css('external.css'); ?>" rel="stylesheet">
    <link href="<?= frontend()->css('internal.css'); ?>" rel="stylesheet">
    <link href="<?= frontend()->js('slick/slick/slick.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css"/>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
          rel="stylesheet">
    <?php
    Extension::load("ui.vue");
    Asset::getInstance()->addJs("https://code.jquery.com/jquery-3.4.1.min.js");
    Asset::getInstance()->addJs("https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js");
    $APPLICATION->ShowHead();
    ?>
    <script src="<?= frontend()->js('slick/slick/slick.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/combine/npm/lightgallery,npm/lg-autoplay,npm/lg-fullscreen,npm/lg-hash,npm/lg-pager,npm/lg-share,npm/lg-thumbnail,npm/lg-video,npm/lg-zoom"
            type="text/javascript"></script>
    <script src="<?= frontend()->js('internal.js'); ?>"></script>


    <!--[if lte IE 8]>
    <link rel="preload" href="<?= frontend()->css('fonts_ie.css'); ?>" as="style">
    <link rel="stylesheet" href="<?= frontend()->css('fonts_ie.css'); ?>">
    <![endif]-->
    <script>
        // IE 9+
        var ready = function(fn) {
            if (typeof fn !== 'function') return;
            if (document.readyState === 'complete') return fn();
            document.addEventListener('DOMContentLoaded', fn, false);
        };
    </script>
</head>
<body>
<?
$APPLICATION->ShowPanel(); ?>
<?
if (!is_directory('/auth')): ?>
    <header class="<?= is_main_page() ? 'header main' : '' ?>">
        <?php
        if (is_main_page()): ?>
            <div class="header-main">
                <div class="header-main__img-wrap"><img src="img/main-head.jpg" alt="" class="header-main__img"></div>
                <div class="header-main__top padding-80">
                    <a href="#" class="header__phone header-link white top">
                        <i class="icon icon-phone"></i>
                        <span>+7 (932) 232 55 55</span>
                    </a>
                    <a href="#" class="header__cart header-link white top">
                        <i class="icon icon-cart"></i>
                    </a>
                    <div class="header-main__logo-wrap"><img src="img/Dermavi_Logo-main.svg" alt=""
                                                             class="header-main__logo">
                    </div>
                </div>
                <div class="header-main__scroll">
                    <i class="icon icon-arrow_down"></i>
                    <i class="icon icon-arrow_down"></i>
                </div>
            </div>
        <?php
        endif; ?>
        <div class="header-wrap padding-80">
            <div class="header-top-wrap">
                <div class="header-top ">
                    <div class="header-top__left">
                        <div class="header__menu js-menu-open header-link mini">
                            <i class="icon icon-menu"></i>
                        </div>
                        <a href="#" class="header__phone header-link">
                            <i class="icon icon-phone"></i>
                            <span>+7 (932) 232 55 55</span>
                        </a>
                        <div class="select-city js-select-wrap">
                            <div class="select-city-wrap">
                                <div class="select-city__label">
                                    <i class="icon icon-loc"></i>
                                    <span>Ваш город:</span>
                                </div>
                                <div class="select-city__value-wrap js-open-wrap">
                                    <div class="select-city__value js-select js-open">Махачкала<span
                                                class="icon icon-arrow_down"></span></div>
                                    <div class="select-city-drop js-drop">
                                        <div class="drop-city__content">
                                            <div data-value="1" data-name="Махачкала"
                                                 class="drop-city__link js-select-item">Махачкала
                                            </div>
                                            <div data-value="2" data-name="Каспийск"
                                                 class="drop-city__link js-select-item selected">Каспийск
                                            </div>
                                            <div data-value="3" data-name="Буйнакск"
                                                 class="drop-city__link js-select-item">
                                                Буйнакск
                                            </div>
                                            <input type="hidden" name="drop-city__link-input" value="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="header-top__center">
                        <a href="/" class="header__logo"><img src="/img/logo.svg" alt="" class="header__logo-img"></a>
                    </div>
                    <div class="header-top__right">
                        <a href="#" class="header__search header-link">
                            <i class="icon icon-search"></i>
                        </a>
                        <div class="header-profile__wrap js-open-wrap">
                            <div class="header__profile header-link js-open">
                                <i class="icon icon-profile"></i>
                                <span>User5656</span>
                                <i class="icon icon-arrow_down"></i>
                            </div>
                            <div class="profile-drop js-drop">
                                <a href="#" class="profile-drop__item">Мои данные</a>
                                <a href="#" class="profile-drop__item">Бонусный счёт</a>
                                <a href="#" class="profile-drop__item">Мои заказы</a>
                                <a href="#" class="profile-drop__item exit">Выход</a>
                            </div>
                        </div>
                        <a href="#" class="header__favorite header-link">
                            <i class="icon icon-heart"></i>
                            <span>Избранное</span>
                        </a>
                        <a href="#" class="header__cart header-link">
                            <i class="icon icon-cart"></i>
                            <span>Корзина (0)</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="header-nav-wrap">
                <? app()->IncludeComponent(
                    'bitrix:menu',
                    'dermavi.catalog',
                    [
                        "ROOT_MENU_TYPE" => "left",
                        "MAX_LEVEL" => "1",
                        "CHILD_MENU_TYPE" => "top",
                        "USE_EXT" => "Y",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "Y",
                        "MENU_CACHE_TYPE" => "N",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => ""
                    ]
                ) ?>
                <div class="header-nav__search">
                    <input type="search" placeholder="Поиск по каталогу"
                           class="input input-normal input-pl-50 input-full_width input-border">
                    <button class="form__btn form__btn-search" type="submit">
                        <div class="form__icon"><i class="icon icon-search"></i></div>
                    </button>
                </div>
                <a href="#" class="header-nav__search-mini">
                    <i class="icon icon-search"></i>
                    <div class="header-nav__search-mini__label">Поиск</div>
                    <!--<input type="search" placeholder="Поиск" class="input input-normal input-full_width input-no_border">-->
                    <!--<button class="form__btn form__btn-search" type="submit"><div class="form__icon"><i class="icon icon-search"></i></div></button>-->
                </a>
            </div>
        </div>
    </header>
<?
endif; ?>
<div class="content">


<?php
/** Шаблон хедера для физ лиц */
?>

<header class="header-main">
    <div class="header-main__top">
        <div class="header-main__top-container header-main__top-container--b2c">
            <button class="header-main__top-menu-btn header-main__top-menu-btn--b2c" data-href="#popup-menu-b2c"
                    data-popup="open">
                <svg class="icon icon--menu-b2c">
                    <use xlink:href="<?=SPRITE_SVG?>#icon-menu_b2c"></use>
                </svg>
            </button>

            <?php $APPLICATION->IncludeComponent('info:for.partners.menu.link', 'header.b2c', []); ?>
            <?php $APPLICATION->IncludeComponent('main:get.in.touch.form', 'header_form_open', []); ?>

            <div class="header-main__social">
                <a href="https://wa.me/<?=get_sprint_option('CONTACT_PHONE_WHATSAPP')?>" class="header-main__social-item">
                    <svg class="icon icon--whatsapp"><use xlink:href="<?=SPRITE_SVG?>#icon-whatsapp"></use></svg>
                </a>
            </div>

            <a href="<?= get_language_version_href_prefix(); ?>/"
                class="header-main__top-logo">
                    <?php if (\App\Helpers\LanguageHelper::isRussianVersion()) : ?>
                        <svg class="icon icon--logo-alrosa"><use xlink:href="<?=SPRITE_SVG?>#icon-logo_mobile_ru"></use></svg>
                    <?php else : ?>
                        <svg class="icon icon--logo-alrosa"><use xlink:href="<?=SPRITE_SVG?>#icon-logo_mobile_en"></use></svg>
                    <?php endif; ?>
                </svg>
            </a>
            <div class="header-main__top-settings header-main__top-settings--b2c">
                <?php $APPLICATION->IncludeComponent('main:currency.selector', 'b2c', ['number' => 2]); ?>
                <button class="search-b2c-call" type="button" data-popup="open" data-href="#popup-search"
                        title="Открыть поиск">
                    <svg class="search-b2c-call__icon icon icon--loupe">
                        <use xlink:href="<?=SPRITE_SVG?>#icon-loupe"></use>
                    </svg>
                </button>
                <?php $APPLICATION->IncludeComponent('main:language.selector', '', []); ?>
            </div>
            <ul class="menu-user menu-user--header-top menu-user--b2c">
                <?php $APPLICATION->IncludeComponent('user:signin', 'icon.header.mobile', []); ?>
                <?php $APPLICATION->IncludeComponent('sale:cart.header', 'mobile', []); ?>
            </ul>
        </div>
    </div>
    <div class="header-main__content header-main__content--b2c <?= \App\Helpers\FrontendHelper::isGreyLogo() ? 'header-main__content--violet' : ''; ?> header-main__content--b2c-main <?= \App\Helpers\FrontendHelper::getHeaderMenuClass(); ?>">
        <div class="header-main__content-item header-main__content-item--b2c-top">
            <div class="header-main__search-b2c header-main__search-b2c--desktop-visible js-b2c-search">
                <?php $APPLICATION->IncludeComponent('main:search', 'search_line_b2c', []); ?>
            </div>

            <?php if (\App\Helpers\LanguageHelper::isRussianVersion()) : ?>
                <a href="/" class="header-main__logo <?= is_main_page() ? '' : 'header-main__logo--violet'; ?>">
                    <svg class="icon icon--logo-alrosa"><use xlink:href="<?=SPRITE_SVG?>#icon-logo_desktop_ru"></use></svg>
                </a>
            <?php else : ?>
                <a href="<?= get_language_version_href_prefix(); ?>/"
                   class="header-main__logo">
                    <svg class="icon icon--logo-alrosa"><use xlink:href="<?=SPRITE_SVG?>#icon-logo_desktop_en"></use></svg>
                </a>
            <?php endif; ?>
            <a href="<?= get_language_version_href_prefix(); ?>/"
               class="header-main__top-logo js-header-search-hidden">
                <?php if (\App\Helpers\LanguageHelper::isRussianVersion()) : ?>
                    <svg class="icon icon--logo-alrosa-white-ru">
                        <use xlink:href="<?=SPRITE_SVG?>#icon-logo_alrosa_white_ru"></use>
                    </svg>
                <?php else : ?>
                    <svg class="icon icon--logo-alrosa">
                        <use xlink:href="<?=SPRITE_SVG?>#icon-logo_desktop_en"></use>
                    </svg>
                <?php endif; ?>
            </a>
            <div class="header-main__actions header-main__actions--b2c">
                <ul class="menu-user">
                    <?php $APPLICATION->IncludeComponent('user:signin', 'icon.header', []); ?>
                    <?php $APPLICATION->IncludeComponent('sale:cart.header', '', []); ?>
                </ul>
            </div>
        </div>
        <div class="header-main__content-item header-main__content-item--bottom">
            <?php $APPLICATION->IncludeComponent('main:header.menu', '', []); ?>
            <?php if (get_code()) : ?>
                <?php
                    $APPLICATION->IncludeComponent('bitrix:breadcrumb', 'custom_top_adaptive', [
                        'START_FROM' => '0',
                        'PATH' => '',
                        'SITE_ID' => \App\Helpers\SiteHelper::getSiteId(),
                    ]);
                ?>
            <?php endif; ?>
        </div>
    </div>
</header>

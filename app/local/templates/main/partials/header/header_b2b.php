<?php
    /** Шаблон хедера для юр лиц */
?>

<header class="header-main <?= is_main_page() ? 'header-main--index' : ''; ?> header-main--mvp">
    <div class="header-main__top">
        <div class="header-main__top-container">
            <button class="header-main__top-menu-btn" data-href="#popup-menu" data-popup="open">
                <svg class="icon icon--menu">
                    <use xlink:href="<?=SPRITE_SVG?>#icon-menu"></use>
                </svg>
            </button>
            <?php $APPLICATION->IncludeComponent('main:search', 'search_line_b2b', []); ?>
            <p class="header-main__top-text js-header-top-text">
                <?php include_bitrix_area('header/middle_title.html'); ?>
            </p>
            <a href="<?= get_language_version_href_prefix(); ?>/"
               class="header-main__top-logo js-header-search-hidden">
                <?php if (\App\Helpers\LanguageHelper::isRussianVersion()) : ?>
                    <svg class="icon icon--logo-alrosa"><use xlink:href="<?=SPRITE_SVG?>#icon-logo_mobile_ru"></use></svg>
                <?php else : ?>
                    <svg class="icon icon--logo-alrosa"><use xlink:href="<?=SPRITE_SVG?>#icon-logo_mobile_en"></use></svg>
                <?php endif; ?>
            </a>
            <?php $APPLICATION->IncludeComponent('main:get.in.touch.form', 'header_form_open', []); ?>
            <div class="header-main__top-settings">
                <?php $APPLICATION->IncludeComponent('main:currency.selector', 'b2b', ['number' => 2]); ?>
                <?php $APPLICATION->IncludeComponent('main:language.selector', '', []); ?>
            </div>
            <ul class="menu-user menu-user--header-top js-header-search-hidden">
                <?php $APPLICATION->IncludeComponent('user:signin', 'icon.header.mobile', []); ?>
                <?php $APPLICATION->IncludeComponent('sale:cart.header', 'b2b_mobile', []); ?>
            </ul>
        </div>
    </div>
    <div class="header-main__content <?= \App\Helpers\FrontendHelper::getHeaderMenuClass(); ?>">
        <div class="header-main__content-item header-main__content-item--top">
            <div class="header-main__actions">
                <ul class="menu-user <?= is_main_page() ? 'menu-user--mb' : ''; ?>">
                    <?php $APPLICATION->IncludeComponent('user:signin', 'icon.header.b2b', []); ?>
                    <?php $APPLICATION->IncludeComponent('sale:cart.header', 'b2b', []); ?>
                </ul>
            </div>
            <?php if (\App\Helpers\LanguageHelper::isRussianVersion()) : ?>
                <a href="/" class="header-main__logo <?= is_main_page() ? '' : 'header-main__logo--small'; ?>">
                    <svg class="icon icon--logo-alrosa"><use xlink:href="<?=SPRITE_SVG?>#icon-logo_desktop_ru"></use></svg>
                </a>
            <?php else : ?>
                <a href="<?= get_language_version_href_prefix(); ?>/"
                   class="header-main__logo <?= is_main_page() ? '' : 'header-main__logo--small'; ?>">
                    <svg class="icon icon--logo-alrosa"><use xlink:href="<?=SPRITE_SVG?>#icon-logo_desktop_en"></use></svg>
                </a>
            <?php endif; ?>
        </div>
        <div class="header-main__content-item header-main__content-item--bottom">
            <?php
                $APPLICATION->IncludeComponent('bitrix:menu', 'header_item_links', [
                    'MENU_CACHE_TYPE' => 'N',
                    'ROOT_MENU_TYPE' => 'header_top_links',
                ]);
            ?>
            <?php if (get_code()) : ?>
                <?php
                $APPLICATION->IncludeComponent('bitrix:breadcrumb', 'custom_top', [
                    'START_FROM' => '0',
                    'PATH' => '',
                    'SITE_ID' => \App\Helpers\SiteHelper::getSiteId(),
                ]);
                ?>
            <?php endif; ?>
        </div>
    </div>
</header>

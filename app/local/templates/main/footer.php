<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

</div>
<?php
if (!is_directory('/auth')): ?>
    <footer class="footer">
        <div class="footer__logo-wrap">
            <a href="/" class="footer__logo"><img src="/img/logo_footer.svg" alt="" class="footer__logo-img"></a>
            <a href="#" class="footer__copy">DERMAVI © Copyright 2020</a>
        </div>
        <div class="footer__content">
            <div class="footer__menu-wrap">
                <div class="footer__menu js-open-wrap">
                    <div class="footer__menu-title js-open">Главное меню <i class="icon icon-arrow_down"></i></div>
                    <? app()->IncludeComponent(
                        'bitrix:menu',
                        'dermavi.bottom',
                        [
                            "ROOT_MENU_TYPE" => "bottom",
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


                </div>
                <div class="footer__menu js-open-wrap">
                    <div class="footer__menu-title js-open">Справка <i class="icon icon-arrow_down"></i></div>
                    <? app()->IncludeComponent(
                        'bitrix:menu',
                        'dermavi.bottom',
                        [
                            "ROOT_MENU_TYPE" => "help",
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
                </div>
                <div class="footer__menu js-open-wrap">
                    <div class="footer__menu-title js-open">Каталог <i class="icon icon-arrow_down"></i></div>
                    <? app()->IncludeComponent(
                        'bitrix:menu',
                        'dermavi.bottom.catalog',
                        [
                            "ROOT_MENU_TYPE" => "left",
                            "MAX_LEVEL" => "0",
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
                </div>
            </div>
            <div class="footer__contact">
                <div class="footer__menu-title">Контакты</div>
                <div class="footer__contact-list">
                    <a href="#" class="footer__menu-social footer-link"><i class="icon icon-phone"></i>+7 (932) 232 55
                        55</a>
                    <a href="#" class="footer__menu-social footer-link"><i
                                class="icon icon-mail"></i>info@dermavi.com</a>
                    <a href="#" class="footer__menu-social footer-link"><i class="icon icon-instagram"></i>dermavi_cosm</a>
                    <a href="#" class="footer__menu-social lang  mt100">
                        <i class="icon icon-globe"></i>
                        <div class="lang-item footer-link">EN</div>
                        <div class="lang-item footer-link active">RUS</div>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <div class="drop-menu padding-80 js-drop-menu">
        <div class="header-top">
            <div class="header-top__left">
                <div class="drop-menu__close js-drop-menu-close"><i class="icon icon-close"></i></div>
            </div>
            <div class="header-top__center">
                <a href="/" class="header__logo"><img src="/img/logo.svg" alt="" class="header__logo-img"></a>
            </div>
            <div class="header-top__right">
                <a href="#" class="header__cart header-link">
                    <i class="icon icon-cart"></i>
                    <span>Корзина (0)</span>
                </a>
            </div>
        </div>
        <div class="drop-menu__wrapper">
            <div class="drop-menu__links">
                <div class="drop-menu__link"><i class="icon icon-profile"></i>Аккаунт</div>
                <div class="drop-menu__link"><i class="icon icon-heart"></i>Избранное</div>
                <div class="drop-menu__link"><i class="icon icon-phone"></i>Контакты</div>
                <div class="drop-menu__link drop js-open-wrap">
                    <div class="drop-menu__link-wrap  js-open"><i class="icon icon-loc"></i>Ваш город</div>
                    <div class="select-city-drop mobile js-drop">
                        <div class="drop-city__content">
                            <a href="#" class="drop-city__link js-select-item">Махачкала</a>
                            <a href="#" class="drop-city__link js-select-item">Каспийск</a>
                            <a href="#" class="drop-city__link js-select-item">Буйнакск</a>
                            <input type="hidden" name="drop-city__link-input" value="1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="drop-menu__search">
                <input type="search" placeholder="Что вы ищете?"
                       class="input input-normal input-pl-50 input-full_width input-border">
                <button class="form__btn form__btn-search" type="submit">
                    <div class="form__icon"><i class="icon icon-search"></i></div>
                </button>
            </div>
            <div class="drop-menu__wrap">

                <div class="drop-menu__list">
                    <div class="drop-menu__item">
                        <div class="drop-menu__item-title js-menu-item-open">
                            Каталог товаров
                            <i class="icon icon-arrow_down"></i>
                        </div>
                        <div class="drop-menu__item-list js-item-list">
                            <div class="drop-menu__item-list__back js-drop-item-close"><i
                                        class="icon icon-arrow_left"></i>Вернуться
                                назад
                            </div>
                            <div class="drop-menu__item-list__title">Каталог товаров</div>
                            <div class="drop-menu__item-list-wrap">
                                <a href="#" class="drop-menu__item-list__item">Антивозрастные средства</a>
                                <a href="#" class="drop-menu__item-list__item">Маски</a>
                                <a href="#" class="drop-menu__item-list__item">Ночной уход</a>
                                <a href="#" class="drop-menu__item-list__item">Отшелушивание</a>
                                <a href="#" class="drop-menu__item-list__item">Очищение</a>
                                <a href="#" class="drop-menu__item-list__item">Уход за губами</a>
                                <a href="#" class="drop-menu__item-list__item">Уход за кожей вокруг глаз</a>
                                <a href="#" class="drop-menu__item-list__item">Патчи</a>
                                <a href="#" class="drop-menu__item-list__item">Увлажнение/питание</a>
                                <a href="#" class="drop-menu__item-list__item">Уход за проблемной кожей</a>
                                <a href="#" class="drop-menu__item-list__item">Сыворотки</a>
                                <a href="#" class="drop-menu__item-list__item">Тонизирование</a>
                            </div>
                        </div>
                    </div>
                    <div class="drop-menu__item">
                        <div class="drop-menu__item-title js-menu-item-open">
                            Продукция для лица
                            <i class="icon icon-arrow_down"></i>
                        </div>
                        <div class="drop-menu__item-list js-item-list">
                            <div class="drop-menu__item-list__back js-drop-item-close"><i
                                        class="icon icon-arrow_left"></i>Вернуться
                                назад
                            </div>
                            <div class="drop-menu__item-list__title">Продукция для лица</div>
                            <div class="drop-menu__item-list-wrap">
                                <a href="#" class="drop-menu__item-list__item">Антивозрастные средства</a>
                                <a href="#" class="drop-menu__item-list__item">Маски</a>
                                <a href="#" class="drop-menu__item-list__item">Ночной уход</a>
                                <a href="#" class="drop-menu__item-list__item">Отшелушивание</a>
                                <a href="#" class="drop-menu__item-list__item">Очищение</a>
                                <a href="#" class="drop-menu__item-list__item">Уход за губами</a>
                                <a href="#" class="drop-menu__item-list__item">Уход за кожей вокруг глаз</a>
                                <a href="#" class="drop-menu__item-list__item">Патчи</a>
                                <a href="#" class="drop-menu__item-list__item">Увлажнение/питание</a>
                                <a href="#" class="drop-menu__item-list__item">Уход за проблемной кожей</a>
                                <a href="#" class="drop-menu__item-list__item">Сыворотки</a>
                                <a href="#" class="drop-menu__item-list__item">Тонизирование</a>
                            </div>
                        </div>
                    </div>
                    <div class="drop-menu__item">
                        <div class="drop-menu__item-title js-menu-item-open">
                            Список брендов
                            <i class="icon icon-arrow_down"></i>
                        </div>
                        <div class="drop-menu__item-list js-item-list">
                            <div class="drop-menu__item-list__back js-drop-item-close"><i
                                        class="icon icon-arrow_left"></i>Вернуться
                                назад
                            </div>
                            <div class="drop-menu__item-list__title">Список брендов</div>
                            <div class="drop-menu__item-list-wrap">
                                <a href="#" class="drop-menu__item-list__item">Антивозрастные средства</a>
                                <a href="#" class="drop-menu__item-list__item">Маски</a>
                                <a href="#" class="drop-menu__item-list__item">Ночной уход</a>
                                <a href="#" class="drop-menu__item-list__item">Отшелушивание</a>
                                <a href="#" class="drop-menu__item-list__item">Очищение</a>
                                <a href="#" class="drop-menu__item-list__item">Уход за губами</a>
                                <a href="#" class="drop-menu__item-list__item">Уход за кожей вокруг глаз</a>
                                <a href="#" class="drop-menu__item-list__item">Патчи</a>
                                <a href="#" class="drop-menu__item-list__item">Увлажнение/питание</a>
                                <a href="#" class="drop-menu__item-list__item">Уход за проблемной кожей</a>
                                <a href="#" class="drop-menu__item-list__item">Сыворотки</a>
                                <a href="#" class="drop-menu__item-list__item">Тонизирование</a>
                            </div>
                        </div>
                    </div>
                    <div class="drop-menu__item">
                        <div class="drop-menu__item-title js-menu-item-open">
                            Новинки
                            <i class="icon icon-arrow_down"></i>
                        </div>
                        <div class="drop-menu__item-list js-item-list">
                            <div class="drop-menu__item-list__back js-drop-item-close"><i
                                        class="icon icon-arrow_left"></i>Вернуться
                                назад
                            </div>
                            <div class="drop-menu__item-list__title">Новинки</div>
                            <div class="drop-menu__item-list-wrap">
                                <a href="#" class="drop-menu__item-list__item">Антивозрастные средства</a>
                                <a href="#" class="drop-menu__item-list__item">Маски</a>
                                <a href="#" class="drop-menu__item-list__item">Ночной уход</a>
                                <a href="#" class="drop-menu__item-list__item">Отшелушивание</a>
                                <a href="#" class="drop-menu__item-list__item">Очищение</a>
                                <a href="#" class="drop-menu__item-list__item">Уход за губами</a>
                                <a href="#" class="drop-menu__item-list__item">Уход за кожей вокруг глаз</a>
                                <a href="#" class="drop-menu__item-list__item">Патчи</a>
                                <a href="#" class="drop-menu__item-list__item">Увлажнение/питание</a>
                                <a href="#" class="drop-menu__item-list__item">Уход за проблемной кожей</a>
                                <a href="#" class="drop-menu__item-list__item">Сыворотки</a>
                                <a href="#" class="drop-menu__item-list__item">Тонизирование</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php
endif; ?>
</body>
</html>

<?php

?>
<div id="popup-ring-size" class="popup popup-size" data-animation="zoom" style="display: none;" data-wrap-class="fancybox-wrap--size">
    <div class="popup__body popup-size__body">
        <h4 class="text-c popup-size__hl">Как узнать размер кольца?</h4>

        <div class="popup-size__content">
            <div class="popup-size__col popup-size__col--pic">
                <svg class="popup-size__image icon icon--palm">
                    <use xlink:href="<?=SPRITE_SVG?>#icon-palm"></use>
                </svg>
            </div>
            <div class="popup-size__col popup-size__col--text">
                <p class="popup-size__text">
                    Оберните отрезок нити или бумажную ленту шириной 3-4 мм вокруг
                    пальца. Не затягивайте слишком сильно.
                </p>
                <p class="popup-size__text">
                    Отметьте точки на нитке или бумаге, где происходит совпадение. Приложите к линейке, чтобы измерить длину.
                </p>

                <p class="popup-size__subtitle">Калькулятор размера</p>
                <div class="popup-size__calculator js-ring-size">
                    <form class="popup-size__form js-ring-size-from" method="" action="" data-validate="true" novalidate>
                        <div class="popup-size__form-field field js-label-animation">
                            <label class="label" for="ring-length">Длина нити, мм</label>
                            <input class="js-placeholder-input js-ring-size-input" id="ring-length"
                                   type="text" data-parsley-type="integer" name="ringLength" value="" data-parsley-min="31" data-parsley-max="79" data-parsley-number>
                        </div>

                        <button class="popup-size__form-btn btn btn--sm btn--transparent js-ring-size-btn" type="submit">рассчитать размер</button>
                    </form>

                    <p class="popup-size__result js-ring-size-result is-invisible">
                        Ваш размер: <span class="popup-size__result-size js-ring-size-number"></span>
                    </p>
                </div>
            </div>
        </div>

        <button class="popup__close" data-popup="close" type="button" title="Закрыть">
            <svg class="icon icon--cross">
                <use xlink:href="<?=SPRITE_SVG?>#icon-cross"></use>
            </svg>
        </button>
    </div>
</div>

<div id="popup-delivery-terms" class="popup popup-delivery-terms" data-wrap-class="fancybox-wrap--delivery-terms"
     data-animation="zoom" style="display: none;">
    <div class="popup__body popup-delivery-terms__body">
        <h2 class="popup-delivery-terms__title">
            Условия доставки и оплаты        </h2>
        <p class="mb-m">
            Доставляем заказы по всей территории России.<br>
            Срок доставки – от 2 до 10 рабочих дней в зависимости от региона, начиная с даты передачи заказа специальному перевозчику.
            <br><br>Покупку также можно забрать в одном из наших офисов в Смоленске и Москве или в одном из наших ювелирных <a target="_blank" href="/customer-service/contacts/">салонов</a>.
        </p>
        <p>
            Способы оплаты:
        </p>
        <ul class="ul-list mb-m">
            <li>банковской картой одной из платежных систем на сайте онлайн;</li>
            <li>переводом по выставленному банковскому счету;</li>
            <li>банковской картой одной из платежных систем в офисе компании.</li>
        </ul>
        <p>
            Продажа сертифицированных бриллиантов и ювелирных изделий из драгоценных металлов физическим лицам на территории Российской Федерации осуществляется согласно Условиям продажи ООО «ЮГ «АЛРОСА»<br>
            <a target="_blank" href="/customer-service/payment-and-shipping/#shipping">Подробнее</a>
        </p>

        <button class="popup__close popup__close--delivery-terms" data-popup="close" type="button" title="Закрыть">
            <svg class="icon icon--cross">
                <use xlink:href="<?=SPRITE_SVG?>#icon-cross"></use>
            </svg>
        </button>
    </div>
</div>

<div id="popup-view-product" class="popup popup-view-product popup--fullscreen" data-fullscreen="true"
     data-wrap-class="fancybox-wrap--fullscreen" data-animation="zoom" style="display: none;"
     data-wrap-class="fancybox-wrap--size">
    <div class="popup__body popup-size__body view-product js-container-view-product-popup">
        <div class="carousel carousel--view-product-thumbs js-thumbs-carousel-quick-view-popup">
            <div class="swiper-wrapper js-product-popup-jewelry-preview">

            </div>
        </div>
        <div class="carousel carousel--view-product js-thumbs-carousel-view-popup">
            <div class="swiper-wrapper
                js-product-popup-jewelry-main-pic">
            </div>
            <button class="swiper-button-prev" type="button">
                <svg class="carousel__nav-icon icon icon--arrow-lg">
                    <use xlink:href="<?=SPRITE_SVG?>#icon-arrow_lg"></use>
                </svg>
            </button>
            <button class="swiper-button-next" type="button">
                <svg class="carousel__nav-icon icon icon--arrow-lg">
                    <use xlink:href="<?=SPRITE_SVG?>#icon-arrow_lg"></use>
                </svg>
            </button>
        </div>
    </div>


    <button class="popup__close js-popup-close" data-popup="close" type="button" title="Закрыть">
        <svg class="icon icon--cross">
            <use xlink:href="<?=SPRITE_SVG?>#icon-cross"></use>
        </svg>
    </button>
</div>

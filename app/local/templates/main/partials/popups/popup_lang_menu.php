<?php use App\Helpers\UrlHelper; ?>
<div id="popup-language" class="popup popup-language popup--wide" data-animation="zoom" style="display: none;">
    <form class="popup__body popup__body-language">
        <h2 class="popup-language__title">Choose a language</h2>

        <div class="radio radio--text popup-language__radio">
            <input class="radio__input js-language-radio" type="radio" name="language" id="ru" value="ru"
                   checked data-link="<?php echo UrlHelper::getPage()?>">
            <label class="radio__label" for="ru">RU</label>

            <input class="radio__input js-language-radio" type="radio" name="language" id="en" value="en" data-link="/en<?php echo UrlHelper::getPage()?>">
            <label class="radio__label" for="en">EN</label>

            <input class="radio__input js-language-radio" type="radio" name="language" id="cn" value="cn" data-link="/cn<?php echo UrlHelper::getPage()?>">
            <label class="radio__label" for="cn">中文</label>
        </div>

        <div class="popup-language__btn">
            <button id="select-language-button" class="btn btn--lg btn--transparent" type="button">Save settings</button>
        </div>

        <button class="popup__close popup__close-language" data-popup="close" type="button" title="Закрыть">
            <svg class="icon icon--cross">
                <use xlink:href="<?=SPRITE_SVG?>#icon-cross"></use>
            </svg>
        </button>
    </form>
</div>
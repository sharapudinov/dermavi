<?php
/** Попап поиска */
?>
<div id="popup-search" class="popup popup--fullscreen popup--search" data-fullscreen="true" data-wrap-class="fancybox-wrap--fullscreen" data-animation="zoom" style="display: none;">
    <div class="popup__body js-b2c-search">
        <?php $APPLICATION->IncludeComponent(
                'main:search',
                'search_line_' . (\App\Helpers\UserHelper::isLegalEntity() ? 'b2b' : 'b2c'),
                []
        ); ?>
        <button class="popup__close" data-popup="close" type="button" title="Закрыть">
            <svg class="icon icon--cross">
                <use xlink:href="<?=SPRITE_SVG?>#icon-cross"></use>
            </svg>
        </button>
    </div>
</div>
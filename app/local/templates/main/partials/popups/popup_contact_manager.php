<?php
/** Шаблон попапа для связи с менеджером */
/** @var array|mixed[] $arParams - Массив параметров */
?>

<div id="popup-contact-manager" class="popup popup-registration" data-animation="zoom" style="display: none;">
    <div class="popup__body popup__body-registration">
        <div class="container container--registration">
            <?php $APPLICATION->IncludeComponent('main:get.in.touch.form', $arParams['template'], [
                'form_type' => 'callback',
                'show_title' => true
            ]); ?>
        </div>
    </div>
</div>

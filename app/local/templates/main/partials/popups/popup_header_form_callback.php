<?php
/**
 * Попап формы обратного звонка
 */
$APPLICATION->IncludeComponent(
    'main:get.in.touch.form',
    'header_form',
    [
        'form_id' => 'request-call-form',
        'form_type' => 'request_call',
    ],
    null,
    [
        'HIDE_ICONS' => 'Y',
    ]
);

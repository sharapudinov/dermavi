<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'js'        => [
        '/local/js/dermavi/email-field/dist/email-field.bundle.js',
    ],
    'rel'       => ['ui.vue'],
    'skip_core' => true,
];

<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'js'        => [
        '/local/js/dermavi/auth/dist/auth.bundle.js',
    ],
    'rel'       => ['ui.vue'],
    'skip_core' => true,
];

<?php

$arResult = array_filter(
    $arResult,
    function ($item) {
        return $item['DEPTH_LEVEL'] === '1';
    }
);


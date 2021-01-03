<?php

$set = [
    'header' => 'Заголовок',
    'text' => 'Текст',
    'linkTitle' => 'Заголовок кнопки',
    'link' => 'Ссылка',
];

$arTemplateParameters = [];
foreach ($set as $k => $val) {
    $arTemplateParameters[$k] = [
        'NAME' => $val,
        'COLS' => 35,
        'ROWS' => 3
    ];
}

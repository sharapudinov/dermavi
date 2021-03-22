<?php

$arUrlRewrite = [
    2  =>
        [
            'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
            'RULE'      => 'alias=$1',
            'ID'        => null,
            'PATH'      => '/desktop_app/router.php',
            'SORT'      => 100,
        ],
    1  =>
        [
            'CONDITION' => '#^/video([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
            'RULE'      => 'alias=$1&videoconf',
            'ID'        => null,
            'PATH'      => '/desktop_app/router.php',
            'SORT'      => 100,
        ],
    4  =>
        [
            'CONDITION' => '#^\\/?\\/mobileapp/jn\\/(.*)\\/.*#',
            'RULE'      => 'componentName=$1',
            'ID'        => null,
            'PATH'      => '/bitrix/services/mobileapp/jn.php',
            'SORT'      => 100,
        ],
    6  =>
        [
            'CONDITION' => '#^/bitrix/services/ymarket/#',
            'RULE'      => '',
            'ID'        => '',
            'PATH'      => '/bitrix/services/ymarket/index.php',
            'SORT'      => 100,
        ],
    3  =>
        [
            'CONDITION' => '#^/online/(/?)([^/]*)#',
            'RULE'      => '',
            'ID'        => null,
            'PATH'      => '/desktop_app/router.php',
            'SORT'      => 100,
        ],
    0  =>
        [
            'CONDITION' => '#^/stssync/calendar/#',
            'RULE'      => '',
            'ID'        => 'bitrix:stssync.server',
            'PATH'      => '/bitrix/services/stssync/calendar/index.php',
            'SORT'      => 100,
        ],
    9  =>
        [
            'CONDITION' => '#^/personal/order/#',
            'RULE'      => '',
            'ID'        => 'bitrix:sale.personal.order',
            'PATH'      => '/personal/order/index.php',
            'SORT'      => 100,
        ],
    10 =>
        [
            'CONDITION' => '#^/personal/#',
            'RULE'      => '',
            'ID'        => 'bitrix:sale.personal.section',
            'PATH'      => '/personal/index.php',
            'SORT'      => 100,
        ],
    8  =>
        [
            'CONDITION' => '#^/catalog/#',
            'RULE'      => '',
            'ID'        => 'bitrix:catalog',
            'PATH'      => '/catalog/index.php',
            'SORT'      => 100,
        ],
    11 =>
        [
            'CONDITION' => '#^/store/#',
            'RULE'      => '',
            'ID'        => 'bitrix:catalog.store',
            'PATH'      => '/store/index.php',
            'SORT'      => 100,
        ],
    5  =>
        [
            'CONDITION' => '#^/rest/#',
            'RULE'      => '',
            'ID'        => null,
            'PATH'      => '/bitrix/services/rest/index.php',
            'SORT'      => 100,
        ],
    7  =>
        [
            'CONDITION' => '#^/blog/#',
            'RULE'      => '',
            'ID'        => 'bitrix:news',
            'PATH'      => '/blog/index.php',
            'SORT'      => 100,
        ],
];

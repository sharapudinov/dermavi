<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$menu = [];
//$menu = array(
//    array(
//        'parent_menu' => 'global_menu_content',
//        'sort' => 400,
//        'text' => Loc::getMessage('GREENSIGHT_GEO_MENU_TITLE'),
//        'title' => Loc::getMessage('GREENSIGHT_GEO_MENU_TITLE'),
//        'url' => 'greensight_geo_index.php',
//        'items_id' => 'menu_references',
//        'items' => array(
//            array(
//                'text' => Loc::getMessage('GREENSIGHT_GEO_SUBMENU_TITLE'),
//                'url' => 'greensight_geo_index.php?param1=paramval&lang=' . LANGUAGE_ID,
//                'more_url' => array('greensight_geo.php?param1=paramval&lang=' . LANGUAGE_ID),
//                'title' => Loc::getMessage('GREENSIGHT_GEO_SUBMENU_TITLE'),
//            ),
//        ),
//    ),
//);

return $menu;

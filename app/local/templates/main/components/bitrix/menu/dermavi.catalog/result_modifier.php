<?php

for ($index = 0, $offset = -1; $index < count($arResult); $index++) {
    if ($arResult[$index]['IS_PARENT'] && $arResult[$index]['DEPTH_LEVEL'] == 1) {
        $offset++;
        $arResult['RECURCIVE'][$offset] = $arResult[$index];
    } elseif ($arResult[$index]['DEPTH_LEVEL'] == 2) {
        $arResult['RECURCIVE'][$offset]['CHILDREN'][] = $arResult[$index];
    }
}
$this->__component->arResultCacheKeys = array_merge($this->__component->arResultCacheKeys, ['RECURCIVE']);


<?php

namespace App\Components\Local;

class GreensightGeoRegionSelectComponent extends \CBitrixComponent
{
    /**
     * TBD
     *
     * Помещает все доступные для выбора регионы в $arResult.
     * Регионы могут получаться из разных мест (элементы инфоблоков, разделы инфоблоков, HL блоки, Битриксовые местоположения и т д и т п)
     * Важно в итоге получить массив вида ['ID' => 1, 'UF_NAME' => 'Москва', 'UF_IMPORTANT' => true]
     */
    private function placeAvailableRegionsInArResult()
    {
//        $this->arResult['availableRegions'] = cache('available_region_for_select', 60, function() {
//            return [
//                'moscow' => ['ID' => 1, 'CODE' => 'moscow', 'UF_NAME' => 'Москва', 'UF_IMPORTANT' => true],
//                'rostov' => ['ID' => 3, 'CODE' => 'rostov', 'UF_NAME' => 'Ростов', 'UF_IMPORTANT' => false],
//                'spb' => ['ID' => 2, 'CODE' => 'spb', 'UF_NAME' => 'СПБ', 'UF_IMPORTANT' => true],
//            ];
//        });
    }

    private function actionShowSelectedRegion()
    {
        $this->arResult['requiresConfirmation'] = !empty($_COOKIE['region_requires_confirmation']);
        $this->includeComponentTemplate();
    }

    private function actionSelectRegion($region)
    {
        if (!$region || !isset($this->arResult['availableRegions'][$region])) {
            return;
        }

        setcookie('region', $region, time() + 3600 * 24 * 30, '/');
        setcookie('region_requires_confirmation', '', time() - 3600, '/');
    }

    private function actionConfirmAutoSelect()
    {
        setcookie('region_requires_confirmation', '', time() - 3600, '/');
    }

    private function placeSelectedRegionInArResult()
    {
        $this->arResult['selectedRegion'] = defined('REGION') && isset($this->arResult['availableRegions'][REGION]) ? $this->arResult['availableRegions'][REGION] : [];
    }

    public function executeComponent()
    {
        global $APPLICATION;
        
        $this->placeAvailableRegionsInArResult();
        $this->placeSelectedRegionInArResult();

        if ($this->isAjax()) {
            $APPLICATION->RestartBuffer();
            switch ($_REQUEST['region_select_action'])
            {
                case 'select_region':
                    $this->actionSelectRegion($_POST['region']);
                break;
                case 'confirm_auto_select':
                    $this->actionConfirmAutoSelect();
                break;
            }
            die();
        } else {
            $this->actionShowSelectedRegion();
        }
    }
    
    private function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}

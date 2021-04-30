<?php

namespace App\Local\Component;

use App\Components\BaseComponent;

/**
 * Класс-контроллер для работы с пагинацией
 * Class MainPagination
 * @package App\Local\Component
 */
class MainPagination extends BaseComponent
{
    const PAGE_KEY = 'pagen_';

    /**
     * Готовим параметры компонеты
     *
     * @param array $arParams - Массив параметров
     *
     * @return array
     */
    public function onPrepareComponentParams(array $arParams): array
    {
        $this->params['ajax_load_component']     = $arParams['ajax_load_component'] ?? $_REQUEST['ajaxLoadComponent'];
        $this->params['ajax_component_template'] = $arParams['ajax_component_template'] ?? $_REQUEST['ajaxComponentTemplate'];
        $this->params['items_block']             = $arParams['items_block'] ?? $_REQUEST['itemsBlock'];
        $this->params['scroll_to']               = $arParams['scroll_to'] ?? $_REQUEST['scrollTo'];
        $this->params['per_page']                = $arParams['per_page'] ?? $_REQUEST['perPage'];
        $this->params['current_page']            = $arParams['current_page'] ?? $_REQUEST['page'];
        $this->params['items_count']             = $arParams['items_count'] ?? $_REQUEST['itemsCount'];

        $this->params['hide_buttons']       = $arParams['hide_buttons'] ?? $_REQUEST['hideButtons'] ?? false;
        $this->params['template']           = $this->getTemplateName();
        $this->params['is_need_update_url'] = (int)($this->getTemplateName() !== 'show_more');

        $this->params['componentParams']      = $arParams['componentParams'] ?? $_REQUEST['componentParams'];
        $this->params['componentParams']      = json_encode($this->params['componentParams']);
        $this->params['pagination_parameter'] = !empty($arParams['pagination_parameter'])
            ? $arParams['pagination_parameter']
            : 'p';
        $this->params['number_of_pages_to_show_berfore_and_after']
                                              = isset($arParams['number_of_pages_to_show_berfore_and_after'])
            ? $arParams['number_of_pages_to_show_berfore_and_after']
            : 1;
        $this->params['base_directory']       = !empty($arParams['base_directory'])
            ? $arParams['base_directory']
            : app()->GetCurPage();
        $this->params['preserve_get_params']  = !empty($arParams['preserve_get_params'])
            ? $arParams['preserve_get_params']
            : [];
        $this->params['type']                 = $arParams['type'] ?? $_REQUEST['type'] ?? 'diamonds';

        return $this->params;
    }

    /**
     * Запускаем компонент
     *
     * @return void
     */
    public function executeComponent(): void
    {
        $pagesCount = intval(ceil($this->params['items_count'] / $this->params['per_page']));

        if ($this->params['current_page'] > $pagesCount) {
            $this->params['current_page'] = $pagesCount;
        }

        for ($i = 1; $i <= $pagesCount; $i++) {
            if ($this->buttonIsActive($i, $pagesCount)) {
                $this->arResult['buttons'][$i] = [
                    'title'        => $i,
                    'url'          => $this->constructUrl($i),
                    'url_static'   => $this->createUrlStatic($i),
                    'page_key'     => (int)$i,
                    'is_current'   => $i == $this->params['current_page'],
                    'is_separator' => false,
                ];
            }
        }

        $this->fillSeparators();
        $this->fillExtraButtons($pagesCount);

        $this->includeComponentTemplate();
    }

    /**
     * Проверяем активность определенной кнопки
     *
     * @param $page
     * @param $pagesCount
     *
     * @return bool
     */
    private function buttonIsActive($page, $pagesCount): bool
    {
        if ($page === 1 || $page === $pagesCount) {
            return true;
        }

        if (abs($page - $this->params['current_page'])
            < ($this->params['number_of_pages_to_show_berfore_and_after'] + 1)) {
            return true;
        }

        return false;
    }

    /**
     * Заполняем массив разделителями
     *
     * @return void
     */
    private function fillSeparators(): void
    {
        $count   = count($this->arResult['buttons']);
        $counter = 0;
        foreach ($this->arResult['buttons'] as $page => $button) {
            $counter++;
            if (!isset($this->arResult['buttons'][$page + 1]) && !$button['is_separator'] && $counter !== $count) {
                $this->arResult['buttons'][$page + 1] = [
                    'is_separator' => true,
                ];
            }
        }

        ksort($this->arResult['buttons']);
    }

    /**
     * Заполняем кнопками
     *
     * @param $pagesCount
     *
     * @return void
     */
    private function fillExtraButtons($pagesCount): void
    {
        $current = $this->params['current_page'];
        $prev    = $current - 1;
        $next    = $current + 1;

        $this->arResult['prevButton'] = $current == 1
            ? []
            : [
                'title'      => 'Назад',
                'url'        => $this->constructUrl($prev),
                'url_static' => $this->createUrlStatic($prev),
                'page_key'   => (int)$prev,
            ];

        $this->arResult['nextButton'] = $current == $pagesCount
            ? []
            : [
                'title'      => 'Вперед',
                'url'        => $this->constructUrl($next),
                'url_static' => $this->createUrlStatic($next),
                'page_key'   => (int)$next,
            ];
    }

    /**
     * Задаем ссылки
     *
     * @param int $page
     *
     * @return string
     */
    private function constructUrl($page): string
    {
        return $this->params['base_directory'] . '?' . $this->createHttpQuery($page);
    }

    /**
     * @param $page
     *
     * @return string
     */
    protected function createHttpQuery($page): string
    {
        $paginationParameter = $this->params['pagination_parameter'];
        $data                = [];
        foreach ($this->params['preserve_get_params'] as $param) {
            $paramValue = $this->request->get($param);
            if (isset($paramValue)) {
                $data[$param] = $paramValue;
            }
        }
        $data[$paginationParameter] = $page;

        return http_build_query($data);
    }

    /**
     * @param $page
     *
     * @return string
     */
    protected function createUrlStatic($page): string
    {
        $pageUrl = str_replace(
            '/' . self::PAGE_KEY . $this->params['current_page'],
            '',
            $this->params['base_directory']
        );

        $pageUrl .= (substr($pageUrl,-1) != '/') ? '/' . self::PAGE_KEY . $page: self::PAGE_KEY . $page;
        return $pageUrl;
    }

}

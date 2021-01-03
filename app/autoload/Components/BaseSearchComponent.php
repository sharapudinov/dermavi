<?php

namespace App\Components;

use App\Core\Search\ElasticSearch;

/**
 * Базовый компонент для вывода результатов поиска через ElasticSearch
 * Class BaseSearchComponent
 *
 * @package App\Components
 *
 * @property string $query
 * @property int $page
 * @property int $pageSize
 */
class BaseSearchComponent extends ExtendedComponent
{
    /** @var int - Количество товаров на одной странице по-умолчанию */
    protected const DEFAULT_PAGE_SIZE = 12;

    /** @var ElasticSearch $elastic */
    protected $elastic;

    /**
     * Определяет параметры компонента
     *
     * @param mixed[] $arParams Массив параметров компонента
     *
     * @return mixed[]
     */
    public function onPrepareComponentParams(array $arParams): array
    {
        $this->query = $_REQUEST['q'];
        $this->page = (int)($this->request->get('p') ?? 1);
        $this->pageSize = static::DEFAULT_PAGE_SIZE;

        $this->elastic = new ElasticSearch();
        $this->elastic->setQuery($this->query)->setPageSize(static::DEFAULT_PAGE_SIZE)->setPage($this->page);

        return $arParams;
    }

    /**
     * Возвращает данные из elasticsearch
     *
     * @param mixed[] $params Массив параметров для запроса
     * @param string[] $sort Сортировка
     *
     * @return mixed[]
     */
    protected function getFromElastic(array $params, array $sort = null): array
    {
        if (!$sort) {
            $this->elastic->setSort(["_score"]);
        }

        $total = 0;
        $ids = [];
        try {
            $response = $this->elastic->getClient()->search($params);
            $total = $response['hits']['total']['value'];
            $ids = array_map(function ($value) {
                return $value['_id'];
            }, $response['hits']['hits']);

        } catch (ElasticsearchException $e) {
            logger()->error('Elastic search error' . $e->getMessage());
        }

        return [$total, $ids];
    }
}

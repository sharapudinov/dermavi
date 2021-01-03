<?php

namespace App\Core\Search;

use App\Helpers\LanguageHelper;
use App\Helpers\UserHelper;
use App\Models\Search\ElasticSearchModel;
use Elasticsearch\Client;

/**
 * Класс, описывающий взаимодействие приложения с elasticsearch
 * Class ElasticSearch
 *
 * @package App\Core\Search
 */
class ElasticSearch
{
    /** @var int $pageSize Количество элементов на странице */
    private $pageSize;

    /** @var int $page текущая странице */
    private $page = 1;

    /** @var string $sort Сортировка */
    private $sort = '_score';

    /** @var string $query Запрос */
    private $query;

    /** @var Client $client */
    private $client;

    /**
     * ElasticSearch constructor.
     */
    public function __construct()
    {
        $elasticSearchModel = new ElasticSearchModel();
        $this->client = $elasticSearchModel->getClient();
    }

    /**
     * Возвращает объект для взаимодействия с elasticsearch
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Записывает в свойство объекта запрос
     *
     * @param string $query Запрос
     *
     * @return ElasticSearch
     */
    public function setQuery(string $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Записывает в объект количество элементов на странице
     *
     * @param int $size
     *
     * @return ElasticSearch
     */
    public function setPageSize(int $size): self
    {
        $this->pageSize = $size;
        return $this;
    }

    /**
     * Записывает в объект номер текущей страницы
     *
     * @param int $page
     *
     * @return ElasticSearch
     */
    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Записывает в объект сортировк
     *
     * @param array $sort
     *
     * @return ElasticSearch
     */
    public function setSort(array $sort): self
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * Возвращает массив параметров для запроса ЮБИ
     *
     * @return mixed[]
     */
    public function getDataForJewelryElasticParams(): array
    {
        return [
            'index' => ElasticSearchModel::ELASTICSEARCH_INDEX_JEWELRY,
            'body' => [
                'size' => $this->pageSize,
                'from' => $this->pageSize * ($this->page -1),
                'sort' => $this->sort,
                "query" => [
                    "bool" => [
                        "should" => [
                            [
                                'query_string' => [
                                    "query" => $this->query,
                                    'default_operator' => 'AND',
                                    'type' => 'cross_fields',
                                ],
                            ],
                            [
                                'nested' => [
                                    'path' => 'skus',
                                    "query" => [
                                        'query_string' => [
                                            "query" => $this->query,
                                            'default_operator' => 'AND',
                                            'type' => 'cross_fields',
                                        ],
                                    ],
                                    "inner_hits" => [
                                        'sort' => [
                                            'skus.price' => [
                                                'order' => 'asc'
                                            ]
                                        ]
                                    ],
                                ],
                            ]
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Возвращает массив параметров для запроса данных о бриллиантах
     *
     * @return mixed[]
     */
    public function getDiamondsDataParams(): array
    {
        if (!$this->sort) {
            $this->sort = [
                '_score',
            ];
        }

        return [
            'index' => ElasticSearchModel::ELASTICSEARCH_INDEX_DIAMONDS,
            'body' => [
                'size' => $this->pageSize,
                'from' => $this->pageSize * ($this->page -1),
                'sort' => $this->sort,
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'query_string' => [
                                    'query' => $this->query,
                                    'default_operator' => 'AND',
                                    'type' => 'cross_fields',
                                ],
                            ],
                            [
                                'query_string' => [
                                    'query' => UserHelper::isLegalEntity(),
                                    'fields' => ['is_legal']
                                ],
                            ],
                        ]
                    ]
                ],
            ],
        ];
    }

    /**
     * Возвращает массив параметров для запроса статей
     *
     * @return mixed[]
     */
    public function getStaticArticleParams(): array
    {
        $index = 'article,static_pages';
        return [
            'index' => $index,
            'body' => [
                'size' => $this->pageSize,
                'from' => $this->pageSize * ($this->page -1),
                'sort' => '_score',
                'query' => [
                    "bool" => [
                        "must" => [
                            'query_string' => [
                                "query" => $this->query,
                                'type' => 'cross_fields',
                                'default_operator' => 'AND'
                            ],
                        ]
                    ]
                ],
            ],
        ];
    }

    /**
     * Возвращает массив параметров для запроса бриллиантов и ЮБИ
     *
     * @return mixed[]
     */
    public function getDiamondJewelryParams(): array
    {
        $index = LanguageHelper::isRussianVersion() ? 'jewelry,diamonds' : 'diamonds';
        return [
            'index' => $index,
            'body' => [
                'suggest' => [
                    'suggest-1' => [
                        'text' => $this->query,
                        'completion' => [
                            'field' => 'search_suggest_' . LanguageHelper::getLanguageVersion(),
                            'size' => 20,
                            'skip_duplicates' => true,
                        ]
                    ]
                ],
            ],
        ];
    }
}

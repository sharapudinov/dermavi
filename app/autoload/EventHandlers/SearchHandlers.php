<?php

namespace App\EventHandlers;

use App\Core\SearchEngine\SearchIndexDatabase;
use App\Models\Blog\Article;
use App\Models\Blog\GridElement;
use App\Models\Search\ElasticSearchModel;
use App\Models\Search\ElasticService;

/**
 * Класс-обработчик для работы с поиском
 * Class SearchHandlers
 *
 * @package App\EventHandlers
 */
class SearchHandlers
{
    /**
     * Добавляем новый/измененный элемент в поисковый индекс
     *
     * @param $arFields - Поля элемента
     *
     * @return void
     */
    public static function indexDbElement(&$arFields): void
    {
        (new SearchIndexDatabase)->indexSpecific($arFields);
    }

    /**
     * @param $arFields
     */
    public static function updateElasticIndex(&$arFields)
    {
        if (!$arFields['ID']) {
            return;
        }

        $iblockId = $arFields['IBLOCK_ID'];

        if ($iblockId==GridElement::iblockID()) {
            $service = ElasticService::getInstance();
            $articles = GridElement::getById($arFields['ID'])->section->articles;

            $articles->each(function(Article $article) use ($service){
                $service->getClient()->updateElement($service->getArticleData()->getDataForElastic($article), ElasticSearchModel::ELASTICSEARCH_INDEX_ARTICLES);
            });
        }

        if ($iblockId == Article::iblockID()) {
            $article = Article::getById($arFields['ID']);
            $service = ElasticService::getInstance();

            $service->getClient()->updateElement($service->getArticleData()->getDataForElastic($article), ElasticSearchModel::ELASTICSEARCH_INDEX_ARTICLES);
        }
    }

    /**
     * @param $arFields
     */
    public static function deleteElasticIndex(&$arFields)
    {
        if (!$arFields['ID']) {
            return;
        }

        $iblockId = $arFields['IBLOCK_ID'];

        if ($iblockId == GridElement::iblockID()) {
            $service  = ElasticService::getInstance();
            $articles = GridElement::getById($arFields['ID'])->section->articles;

            if ($articles) {
                $articles->each(function (Article $article) use ($service) {
                    $service->getClient()->updateElement($service->getArticleData()->getDataForElastic($article), ElasticSearchModel::ELASTICSEARCH_INDEX_ARTICLES);
                });
            }
        }

        if ($iblockId == Article::iblockID()) {
            try {
                ElasticService::getInstance()->getClient()->deleteElement($arFields['ID'], ElasticSearchModel::ELASTICSEARCH_INDEX_ARTICLES);
            } catch (\Exception $e) {

            }
        }
    }

}

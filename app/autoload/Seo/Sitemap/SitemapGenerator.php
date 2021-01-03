<?php

namespace App\Seo\Sitemap;

use App\Helpers\JewelryHelper;
use App\Models\Blog\Article;
use App\Models\Catalog\Diamond;
use App\Models\Catalog\DiamondSection;
use App\Models\Jewelry\Jewelry;
use App\Models\Jewelry\JewelrySection;
use App\Models\Seo\SitemapUrl;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Arrilot\BitrixModels\Queries\ElementQuery;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\IO\Directory;
use DOMDocument;
use Illuminate\Support\Collection;

class SitemapGenerator
{
    /**
     * Путь к папке с sitemap относительно корня публичной части сайта
     */
    public const SITEMAP_DIRECTORY = '';

    /**
     * Название файла sitemap
     */
    public const SITEMAP_FILE_NAME = 'sitemap.xml';

    /**
     * Значения для priority по умолчанию
     */
    public $priorityDefault = 0.7;

    /**
     * Значения для changefreq по умолчанию
     */
    public $changefreqDefault = 'monthly';

    /** @var DomDocument $xml */
    private $xml;

    private $urlset;

    private $domain;

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    public function __construct()
    {
        $this->xml = new DomDocument('1.0', 'utf-8'); //создаем новый экземпляр
        $this->urlset = $this->xml->appendChild($this->xml->createElement('urlset')); // добавляем тег urlset
        $this->urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9'); //атрибуты/
        $this->domain = 'https://' . Option::get('main', 'server_name');
    }

    public function run(): void
    {
        $fileName = $this->getFilePath(static::SITEMAP_FILE_NAME);
        $staticPages = SitemapUrl::getList();

        foreach ($staticPages as $page) {
            $this->createUrl($page->getUrl(), $page->getChangefreq(), $page->getPriority());
        }

        /** @var Collection|JewelrySection[] $sections Коллекция моделей разделов */
        $jewelrySections = JewelrySection::getList();

        foreach ($jewelrySections as $jewelrySection) {
            $this->createUrl($jewelrySection->getSectionUrl(), 'daily', 0.9);
        }

        /** @var Collection|Jewelry[] $jewelryProducts Коллекция ювелирных украшений */
        $jewelryProducts = Jewelry::active()
                                  ->with(
                                      [
                                          'activeSkus' => function (BaseQuery $query) {
                                              $query->filter(
                                                  /** @todo Сделать общий метод фильтра для предложений ювелирки */
                                                  [
                                                      'ACTIVE' => 'Y',
                                                      '=PROPERTY_SELLING_AVAILABLE_VALUE' => 'Y',
                                                  ]
                                              );
                                          },
                                      ]
                                  )
                                  ->getList();

        JewelryHelper::removeInactive($jewelryProducts);

        foreach ($jewelryProducts as $jewelryProduct) {
            $this->createUrl($jewelryProduct->getDetailPageUrl(), 'daily', 0.9);
        }

        /** @var ElementQuery $resProducts - Объект, описывающий запрос */
        $diamondProducts = Diamond::query()
            ->fromSectionWithCode(DiamondSection::FOR_PHYSIC_PERSONS_SECTION_CODE)
            ->filter(
                [
                  'ACTIVE'                            => 'Y',
                  '=PROPERTY_SELLING_AVAILABLE_VALUE' => 'Y',
                  '!PROPERTY_PRICE'                   => false,
                ]
            )
            ->getList();

        foreach ($diamondProducts as $diamondProduct) {
            $this->createUrl($diamondProduct->getDetailPageUrl(), 'daily', 0.9);
        }

        /** @var Collection|Article[] $articleList Коллекция статей */
        $articleList = Article::active()->filter(['ACTIVE_DATE' => 'Y'])->getList();
        foreach ($articleList as $article) {
            $this->createUrl($article->getDetailPageUrl(), 'monthly', 0.8);
        }

        $this->xml->formatOutput = true; // устанавливаем выходной формат документа в true
        $this->xml->save($fileName); // сохраняем файл
    }

    /**
     * Создаёт элемент url
     *
     * @param string $uv
     * @param string $changefreqVal
     * @param string $priorityVal
     */
    public function createUrl(string $uv, string $changefreqVal = '', string $priorityVal = ''): void
    {
        $url = $this->urlset->appendChild($this->xml->createElement('url'));
        $loc = $url->appendChild($this->xml->createElement('loc'));
        $urlVal = $this->domain . $uv;

        if (mb_substr($urlVal, -1) !== '/') {
            $urlVal .= '/';
        }
        $loc->appendChild($this->xml->createTextNode($urlVal));

        $changefreq = $url->appendChild($this->xml->createElement('changefreq'));
        $changefreq->appendChild(
            $this->xml->createTextNode($changefreqVal ?: $this->changefreqDefault)
        );
        $priority = $url->appendChild($this->xml->createElement('priority'));
        $priority->appendChild($this->xml->createTextNode($priorityVal ?: $this->priorityDefault));
    }

    /**
     * Возвращает абсолютный путь к файлу sitemap
     *
     * @param string $fileName
     *
     * @return string
     */
    public function getFilePath(string $fileName): string
    {
        $dirName = sprintf('%s%s', Application::getDocumentRoot(), static::SITEMAP_DIRECTORY);

        if (!is_dir($dirName)) {
            Directory::createDirectory($dirName);
        }

        return sprintf('%s/%s', $dirName, $fileName);
    }
}

<?php

namespace App\Feed;

use App\Core\Catalog\FilterFields\DiamondsFilterFields;
use App\Models\Catalog\Diamond;
use App\Models\Catalog\DiamondSection;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;
use DOMDocument;
use Illuminate\Support\Collection;
use RuntimeException;

/**
 * Генератор фида бриллиантов для Google Ads
 * @package App\Feed
 */
class DiamondsGoogleAdsGenerator extends BaseGenerator
{
    public const FEED_FILE_NAME = 'diamonds-google-ads-kdRh4wFk.xml';

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    public function run()
    {
        $fileName = $this->getFilePath(static::FEED_FILE_NAME);

        $url = 'https://' . Option::get('main', 'server_name');
        $vendor = 'АЛРОСА';
        $shapeSamplePath = frontend()->img('shape_samples/');
        $sectionCode = DiamondSection::FOR_PHYSIC_PERSONS_SECTION_CODE;

        /** @var DiamondSection $section Модель раздела бриллиантов для физ лиц */
        $section = DiamondSection::filter(['CODE' => $sectionCode])->first();

        if (!$section instanceof DiamondSection) {
            throw new RuntimeException(
                sprintf('Not fond section with code %s', $sectionCode)
            );
        }

        /** @var array|mixed[] $filter - Фильтр */
        $filter = DiamondsFilterFields::getFilterForCatalogDiamonds();

        /** @var Diamond $diamonds - Коллекция бриллиантов */
        $resProducts = Diamond::query()
            ->fromSectionWithCode($section->getCode())
            ->filter($filter);

        /** @var Collection|Diamond[] $products - Коллекция бриллиантов */
        $products = $resProducts->getList();

        foreach ($products as $product) {
            /** @var Diamond $product - Бриллиант */
            $product->setPhotos(true);
        }

        $xml = new DomDocument('1.0', 'utf-8'); //создаем новый экземпляр<
        $rss = $xml->appendChild($xml->createElement('rss')); // добавляем тег rss
        /** @noinspection PhpUndefinedMethodInspection */
        $rss->setAttribute('version', '2.0'); //атрибуты
        /** @noinspection PhpUndefinedMethodInspection */
        $rss->setAttribute('xmlns:g', 'http://base.google.com/ns/1.0');//атрибуты/
        // канал
        $items = $rss->appendChild($xml->createElement('channel'));
        // заголовок
        $main_title = $items->appendChild($xml->createElement('title'));
        $main_title->appendChild($xml->createTextNode($vendor));
        // ссылка на магазин
        $main_link = $items->appendChild($xml->createElement('link'));
        $main_link->appendChild($xml->createTextNode($url));
        // описание магазина
        $main_desc = $items->appendChild($xml->createElement('description'));
        $main_desc->appendChild(
            $xml->createTextNode(
                sprintf(
                    'Официальный интернет-магазин ювелирного завода %s предлагает купить эксклюзивные бриллианты и ювелирные изделия с бриллиантами по ценам производителя!',
                    $vendor
                )
            )
        );

        // описание оферов
        foreach ($products as $product) {
            $shape = $product->getShape();
            $form = $shape ? $shape->getDisplayValue() : '';

            $name = 'Бриллиант';
            // форма
            if ($form) {
                $name .= ' ' . strtolower($form);
            }
            // цвет
            if ($color = $product->getColorValue()) {
                $name .= ' цвет ' . $color;
            }
            // чистота
            if ($clarity = $product->getClarityValue()) {
                $name .= ' чистота ' . $clarity;
            }
            // id, в качестве id выводим номер пакета
            if ($packetNumber = $product->getPacketNumber()) {
                $name .= ' ' . $packetNumber;
            }

            $descriptionValue = 'Этот прекрасный бриллиант '.
                $product->getWeight() .' карат, форма: '.
                $product->getShapeValue('', 'ru').', имеет '.
                trim($product->getCut()['UF_NAME']).' огранку';

            $image = $product->getPhotos()[0] ?? $shapeSamplePath . $product->getShape(
                )['UF_XML_ID'] . '.jpg'; // картинка из карточки или заглушка
            /** @var Diamond $product - Коллекция бриллиантов */
            $item = $items->appendChild($xml->createElement('item'));
            // ID украшения
            $id = $item->appendChild($xml->createElement('g:id'));
            $id->appendChild($xml->createTextNode($product->getIDForFeed()));
            // название украшения
            $title = $item->appendChild($xml->createElement('g:title'));
            $title->appendChild($xml->createTextNode($name));
            // описание украшения
            $description = $item->appendChild($xml->createElement('g:description'));
            $description->appendChild($xml->createTextNode($descriptionValue)); //@TODO сейчас они все будут "Бриллиант", т. к. нет других типов камней
            // ссылка на детальную страницу украшения
            $vendor_link = $item->appendChild($xml->createElement('g:link'));
            $vendor_link->appendChild($xml->createTextNode($url . $product->getDetailPageUrl()));
            // тег новый товар
            $condition = $item->appendChild($xml->createElement('g:condition'));
            $condition->appendChild($xml->createTextNode('new'));
            // тег доступен товар
            $availability = $item->appendChild($xml->createElement('g:availability'));
            $availability->appendChild($xml->createTextNode('in stock'));
            // стоимость украшения
            $price = $item->appendChild($xml->createElement('g:price'));
            $price->appendChild(
                $xml->createTextNode(
                    sprintf('%s RUB', $product->getPriceForFeed())
                )
            );
            // производитель украшения
            $price = $item->appendChild($xml->createElement('g:brand'));
            $price->appendChild($xml->createTextNode($vendor));
            // ID Категория Google
            $price = $item->appendChild($xml->createElement('g:google_product_category'));
            $price->appendChild($xml->createTextNode(188));
            // Тип категории Google
            $price = $item->appendChild($xml->createElement('g:product_type'));
            $price->appendChild($xml->createTextNode('Предметы одежды и принадлежности > Ювелирные украшения'));
            // Картинка украшения
            $image_link = $item->appendChild($xml->createElement('g:image_link'));
            $image_link->appendChild($xml->createTextNode($url . $image));
        }
        $xml->formatOutput = true; // устанавливаем выходной формат документа в true
        $xml->save($fileName); // сохраняем файл
    }
}

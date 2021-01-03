<?php

namespace App\Feed;

use App\Core\Catalog\FilterFields\DiamondsFilterFields;
use App\Core\Currency\Currency;
use App\Helpers\Market\YmlDocument;
use App\Helpers\Market\YmlOffer;
use App\Helpers\PriceHelper;
use App\Models\Catalog\Diamond;
use App\Models\Catalog\DiamondSection;
use Arrilot\BitrixModels\Queries\ElementQuery;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;
use RuntimeException;

/**
 * Генератор фида бриллиантов для Яндекс.Маркета
 * @package App\Feed
 */
class DiamondsYandexMarketGenerator extends BaseGenerator
{
    public const FEED_FILE_NAME = 'diamonds-yandex-market-EGQ4v60j.xml';

    /**
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    public function run(): void
    {
        $sectionCode = DiamondSection::FOR_PHYSIC_PERSONS_SECTION_CODE;
        $shapeSamplePath = frontend()->img('shape_samples/');

        /** @var DiamondSection $section Модель раздела бриллиантов для физ лиц */
        $section = DiamondSection::filter(['CODE' => $sectionCode])->first();

        if (!$section instanceof DiamondSection) {
            throw new RuntimeException(
                sprintf('Not fond section with code %s', $sectionCode)
            );
        }

        /** @var array|mixed[] $filter - Фильтр */
        $filter = DiamondsFilterFields::getFilterForCatalogDiamonds();

        /** @var ElementQuery $resProducts - Объект, описывающий запрос */
        $resProducts = Diamond::query()
            ->fromSectionWithCode($section->getCode())
            ->filter($filter);

        /** @var \Illuminate\Support\Collection|Diamond[] $products - Коллекция бриллиантов */
        $products = $resProducts->getList();

        foreach ($products as $product) {
            /** @var Diamond $product - Бриллиант */
            $product->setPhotos(true);
        }

        $fileName = $this->getFilePath(static::FEED_FILE_NAME);
        $url = 'https://' . Option::get('main', 'server_name');
        $y = new YmlDocument(
            'ООО Ювелирная группа "АЛРОСА"',
            'ООО Ювелирная группа "АЛРОСА"'
        );
        $y->formatOutput = true; // устанавливаем выходной формат документа в true
        $y->fileName($fileName); // имя файла
        $y->bufferSize(1024 * 1024 * 16); // буфер
        $y->url($url); // адрес магазина
        $y->cms('1C-Bitrix', SM_VERSION); // CMS: название, [версия] они же 'platform' и 'version'
        $y->currency('RUR', 1);
        $y->category($section->getId(), $section->getFields()['NAME']);

        /** @var Diamond $product */
        foreach ($products as $product) {
            $fields = $product->getFields();
            $shape = $product->getShape();
            $image = $product->getPhotos()[0] ?? $shapeSamplePath . $product->getShape(
                )['UF_XML_ID'] . '.jpg'; // картинка из карточки или заглушка
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

            /** @var YmlOffer $offer - Коллекция украшений */
            $offer = $y->arbitrary(
                $fields['NAME'],
                'АЛРОСА',
                $fields['NAME'],
                $product->getPriceForFeed(),
                Currency::RUB_CURRENCY,
                (int)$product->getSectionId(),
                null,
                false
            );

            $offer->name($name);
            $offer->url($url . $fields['DETAIL_PAGE_URL']); // !!!  условно обязательный. URL страницы товара
            /** @noinspection PhpUndefinedMethodInspection */
            $offer->vendorCode($product->getCode()); // Длинный символьный код товара из торгового предложения (артикул) - 0B018E21
            $offer->available(true); // под заказ
            /** @noinspection PhpUndefinedMethodInspection */
            $offer->pic($url . $image); // картинка бриллианта
            /** @noinspection PhpUndefinedMethodInspection */
            $offer->sale('Необходима предоплата 100%.'); // sales_notes, минимальные суммы и партии, наличие скидок и т.д.
            /** @noinspection PhpUndefinedMethodInspection */
            $offer->delivery(true); // Возможно доставить.
            /** @noinspection PhpUndefinedMethodInspection */
            $offer->pickup(); // Возможен самовывоз
            $offer->add('market_category', 'Ювелирные украшения');
            /** @noinspection PhpUndefinedMethodInspection */
            $offer->origin('Россия'); // страна производитель из списка Яндекса. Иногда желательно указывать
            /** @noinspection PhpUndefinedMethodInspection */
            $offer->param('Форма', $form);
            $color = $product->getColor();
            /** @noinspection PhpUndefinedMethodInspection */
            $offer->param('Цвет', $color ? $color->getDisplayValue() : '');
            /** @noinspection PhpUndefinedMethodInspection */
            $offer->param('Вес бриллианта', $product->getWeight('.'));
        }
    }
}

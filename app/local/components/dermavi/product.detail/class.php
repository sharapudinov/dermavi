<?php

namespace App\Local\Component;

use App\Components\BaseComponent;
use App\Core\Currency\Currency;
use App\Core\User\User;
use App\Helpers\LanguageHelper;
use App\Helpers\SiteHelper;
use App\Helpers\TTL;
use App\Helpers\UserHelper;
use App\Models\Catalog\Catalog;
use App\Models\Catalog\CatalogSku;
use App\Seo\GlobalSiteTag\GlobalSiteTagEvent;
use App\Seo\GlobalSiteTag\GlobalSiteTagHandler;
use Arrilot\BitrixCacher\AbortCacheException;
use Bitrix\Catalog\PriceTable;
use Bitrix\Catalog\ProductTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Iblock\Iblock;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Iblock\Elements\ElementOffersTable;

/**
 * Класс-контроллер для детального просмотра товара
 * Class CatalogProductDetail
 * @package App\Local\Component
 */
class CatalogProductDetail extends BaseComponent
{


    /** @var array|array[] $params Массив, описывающий настройки параметров компонента */
    protected $params = [
        'product_code' => ['type' => 'string', 'default' => null], // Идентификатор бриллианта
        'from'         => ['type' => 'string', 'default' => null], // Начиная с

        'OFFERS_FIELD_CODE' => [
            'type'    => 'array',
            'default' => [
                'ID',
                'CODE',
                'NAME',
                'PREVIEW_PICTURE',
                'DETAIL_PICTURE'
            ]
        ],

        'OFFERS_PROPERTY_CODE' => [
            'type'    => 'array',
            'default' => [
                'ARTNUMBER',
                'COLOR_REF',
            ]
        ],
    ];

    /**
     * Запускаем компонент
     *
     * @return void
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public function executeComponent(): void
    {
        $this->loadData();

        $this->includeComponentTemplate();
    }

    /**
     * Загружаем данные для карточки товара
     *
     * @return void
     * @throws \Bitrix\Main\ArgumentNullException
     */
    private function loadData(): void
    {
        if (!$this->arParams['product_code']) {
            $this->show404();
        }

        /** @var string $cacheKey - Ключ кеширования */
        $cacheKey = get_class_name_without_namespace(self::class)
            . '_' . SiteHelper::getSiteId()
            . '_' . Currency::getCurrentCurrency()->getSymCode()
            . '_' . $this->arParams['product_code'];

        $cacheInitDir = Catalog::CATALOG_CACHE_INIT_DIR . '_' . $this->arParams['product_id'];

        $this->arResult = cache(
            $cacheKey,
            TTL::DAY,
            function () use ($cacheInitDir) {
                cache_manager()->StartTagCache($cacheInitDir);

                $filter = [
                    'CODE' => $this->arParams['product_code']
                ];

                /** @var \Bitrix\Main\ORM\Query\Query $catalogQuery */
                $catalogQuery = Catalog::query()
                    ->setFilter($filter);

                $objCatalog = $catalogQuery->fetchObject();
                $arCatalog = $catalogQuery->fetch();

                if (!$arCatalog) {
                    throw new AbortCacheException();
                }
                $recommend = [];
                foreach ($objCatalog->getRecommend()->getAll() as $linkElement) {
                    $recommend[] = $linkElement->getElement()->getId();
                }
                $isSku = $arCatalog['TYPE'] == ProductTable::TYPE_SKU;


                if ($isSku) {
                    /** @var \Bitrix\Main\ORM\Query\Query $offersQuery */
                    $offersQuery = CatalogSku::query();
                    $offersQuery->setFilter(
                        [
                            'CML2_LINK.VALUE' => $objCatalog->getId()
                        ]
                    );
                    $offersArray = $offersQuery->fetchAll();
                }
                foreach ($offersArray as $offer) {
                    $price = \CCatalogProduct::GetOptimalPrice(
                        $offer['ID'],
                        1,
                        User::GetUserGroupArray(),
                        'N'
                    );
                    if (is_set($offers[$offer['ID']])) {
                        $offers[$offer['ID']]['more_photo'][] = \CFile::GetPath($offer['MORE_PHOTO_FILE']);
                        continue;
                    }
                    $offers[$offer['ID']] = [
                        'id'             => $offer['ID'],
                        'name'           => $offer['NAME'],
                        'photo'          => \CFile::GetPath($offer['DETAIL_PICTURE']),
                        'more_photo'     => [\CFile::GetPath($offer['MORE_PHOTO_FILE'])],
                        'quantity'       => $offer['QUANTITY'],
                        'articul'        => $offer['ARTICUL'],
                        'color'          => [
                            'name' => $offer['COLOR_NAME'],
                            'file' => \CFile::GetPath($offer['COLOR_FILE']),
                            'id'   => $offer['COLOR_ID']
                        ],
                        'volume'         => $offer['VOLUME_VALUE'],
                        'price'          => $price['RESULT_PRICE']['BASE_PRICE'],
                        'discount_price' => $price['RESULT_PRICE']['DISCOUNT_PRICE'],
                        'discount'       => $price['RESULT_PRICE']['PERCENT'],
                    ];
                }

                cache_manager()->RegisterTag('catalog_product');
                cache_manager()->EndTagCache();

                return [
                    'id'          => $objCatalog->getId(),
                    'name'        => $objCatalog->getName(),
                    'articul'     => $objCatalog->getArtnumber() ? $objCatalog->getArtnumber()->getValue() : '',
                    'photo'       => \CFile::GetPath($objCatalog->getDetailPicture()),
                    'more_photo'  => array_map(
                        function ($item) {
                            return '/upload/' .
                                $item->getFile()->getSubdir() . '/' . $item->getFile()->getFileName();
                        },
                        $objCatalog->getMorePhoto()->getAll()
                    ),
                    'product'     => $arCatalog,
                    'offers'      => $offers,
                    'is_sku'      => $isSku,
                    'hit'         => $objCatalog->getSaleleader() ? $objCatalog->getSaleleader()->getValue() : false,
                    'new_product' => $objCatalog->getNewproduct() ? $objCatalog->getNewproduct()->getValue() : false,
                    'video'       => $objCatalog->getVideo() ? $objCatalog->getVideo()->getValue() : false,
                    'structure'   => $objCatalog->getStructure() ? $objCatalog->getStructure()->getValue() : false,
                    'application' => $objCatalog->getApplication() ? $objCatalog->getApplication()->getValue() : false,
                    'description' => $objCatalog->getDetailText(),
                    'recommend'   => $recommend,
                ];
            },
            $cacheInitDir
        );


//        set_title($data['product_name']);

        /** Устанавливаем товар в куку, как просмотренный */
//        ProductHelper::setProductToSeen((string)$this->arParams['product_id']);

        $this->arResult['languageVersion'] = LanguageHelper::getLanguageVersion();
    }

    /**
     * Добавляет событие gtag
     */
    private
    function gtagAddEvent(): void
    {
        /** @var Catalog|null $product */
        $product = $this->arResult['product'] ?? null;

        if (!$product) {
            return;
        }

        (new GlobalSiteTagHandler)->addEvent(
            (new GlobalSiteTagEvent)
                ->setName('view_item')
                ->setValue(
                    sprintf('%s RUB', $product->getPriceForFeed())
                )
                ->setItems(
                    [
                        [
                            'id'                       => $product->getIDForFeed(),
                            'google_business_vertical' => 'retail',
                        ]
                    ]
                )
        );
    }
}

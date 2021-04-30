<?php

namespace App\Local\Component;

use App\Components\BaseComponent;
use App\Core\Catalog\FilterFields\ProductFilterFields;
use App\Core\Catalog\TopFilterRules\AbstractTopFilterRule;
use App\Core\Currency\Currency;
use App\Core\Sale\Entity\CartType\DefaultCartType;
use App\Core\Sale\UserCart;
use App\Core\Traits\ProductsListTrait;
use App\Core\Traits\Sale\StashedProductTrait;
use App\Core\User\User;
use App\Helpers\LanguageHelper;
use App\Helpers\TTL;
use App\Models\Catalog\Catalog;
use Bitrix\Catalog\PriceTable;
use Bitrix\Catalog\ProductTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Sale\BasketItem;
use Illuminate\Support\Collection;

/**
 * Класс-контроллер для работы со списком товаров
 * Class CatalogProductList
 * @package App\Local\Component
 */
class CatalogProductList extends BaseComponent
{
    /** Используем трейт для работы с отложенными товарами */
    use StashedProductTrait;

    /** Трейт для работы со списком товаров */
    use ProductsListTrait;

    const SELECT =
        [
            "SECTION_NAME"    => "SECTION.NAME",
            "SECTION_CODE"    => "SECTION.CODE",
            "PRICE"           => "PRICE_LIST.PRICE",
            "OLD_PRICE"       => "PRICE_LIST.PRICE",
            "QUANTITY"        => "PRODUCT.QUANTITY",
            'DETAIL_PAGE_URL' => 'IBLOCK.DETAIL_PAGE_URL'
        ];
    /** @var string - Название шаблона для пустой товарной выдачи */
    private const EMPTY_TEMPLATE = 'empty';

    /** @var string $template - Шаблон компонента */
    private $template;

    /** @var string $template - Шаблон компонента */
    private $productIdsInCart = [];

    /**
     * @var array Доступные правила для отбора бриллиантов в ТОП
     */
    private static $availableRules = [
    ];

    /**
     * @var Collection|AbstractTopFilterRule[] Выбранные правила
     */
    private $rules;

    /** @var array Параметры компонента */
    protected $params = [
        'VIEW'           => ['type' => 'string', 'default' => null],   /* Режим начального отображения table/cell */
        'IS_B2C'         => ['type' => 'bool', 'default' => false],     /* Режим каталога b2c/b2b */
        'PAGE_SIZE'      => ['type' => 'int', 'default' => 16],
        'filter'         => ['type' => 'boolean', 'default' => false],
        'FIELD_CODE'     => [
            'type'    => 'array',
            'default' => ['ID', 'CODE', 'IBLOCK_ID', 'NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE']
        ],
        'PROPERTY_CODE'  => [
            'type'    => 'array',
            'default' => [/*'ARTNUMBER', 'MANUFACTURER', 'SALELEADER', 'NEWPRODUCT'*/]
        ],
        'PICTURE_WIDTH'  => ['type' => 'int', 'default' => 327],
        'PICTURE_HEIGHT' => ['type' => 'int', 'default' => 378]
    ];

    /**
     * Загружаем данные
     *
     * @return void
     */
    private function loadData(): void
    {
        /** @var \App\Core\Currency\Entity\CurrencyEntity $currency - Текущая валюта */
        $currency = Currency::getCurrentCurrency();

        /** @var array|mixed[] $filter - Фильтр */
        $filter = ProductFilterFields::getFilterForCatalog();
        if ($this->arParams['SECTION_CODE']) {
            $filter = array_merge(
                $filter,
                ['SECTION_CODE' => $this->arParams['SECTION_CODE']]
            );
        }
        if (is_array($this->arParams['FILTER'])) {
            $filter = array_merge(
                $filter,
                $this->arParams['FILTER']
            );
        }

        $cacheKey = get_class_name_without_namespace(self::class)
            . '_' . LanguageHelper::getLanguageVersion()
            . '_' . $currency->getSymCode()
            . '_' . $this->getPage()
            . '_' . $this->arParams['PAGE_SIZE']
            . '_' . $this->getSortBy()
            . '_' . $this->getOrder()
            . '_' . $this->getFilterHash($filter);


        $cacheInitDir = Catalog::CATALOG_CACHE_INIT_DIR;

        $this->arResult = cache(
            $cacheKey,
            0,
            function () use ($filter, $cacheInitDir) {
                Loader::includeModule('sale');
                cache_manager()->StartTagCache($cacheInitDir);
                $select = array_merge(
                    $this->arParams['FIELD_CODE'],
                    $this->arParams['PROPERTY_CODE'],
                    self::SELECT
                );


                $price_list = new Reference(
                    'PRICE_LIST',
                    PriceTable::class,
                    Join::on('this.ID', 'ref.PRODUCT_ID')
                );

                $product = new Reference(
                    'PRODUCT',
                    ProductTable::class,
                    Join::on('this.ID', 'ref.ID')
                );
                $section = new Reference(
                    'SECTION',
                    SectionTable::class,
                    Join::on('this.IBLOCK_SECTION_ID', 'ref.ID')
                );

                $productQuery = Catalog::query()
                    ->setSelect($select)
                    ->registerRuntimeField($price_list)
                    ->registerRuntimeField($product)
                    ->registerRuntimeField($section)
                    ->setFilter($filter)
                    ->setOffset(($this->getPage() - 1) * $this->arParams['PAGE_SIZE'])
                    ->setLimit($this->arParams['PAGE_SIZE']);

                if (isset($this->sortBy) && isset($this->order)) {
                    $productQuery->setOrder([$this->sortBy => $this->order]);
                }

                $count = $productQuery->queryCountTotal();

                $page_count = ceil($count / $this->arParams['PAGE_SIZE']);
                //Если страница больше максимальной, то отображаем последнюю
                if ($count < $this->getPage() * $this->arParams['PAGE_SIZE']) {
                    $productQuery->setOffset(
                        ($page_count ? ($page_count - 1) : 0) * $this->arParams['PAGE_SIZE']
                    );
                }

                $arProducts = $productQuery->fetchAll();
                foreach ($arProducts as $key => $product) {
                    $products[$product['ID']] = $product;
                    $products[$product['ID']]['DETAIL_PAGE_URL'] = \CIBlock::ReplaceDetailUrl(
                        $product ['DETAIL_PAGE_URL'],
                        $product,
                        false,
                        'E'
                    );
                    $path = \CFile::GetPath(
                        $product['PREVIEW_PICTURE']
                    );
                    $file_array = \CFile::GetFileArray( $product['PREVIEW_PICTURE']);
                    $products[$product['ID']]['PREVIEW_PICTURE'] = \CFile::ResizeImageGet(
                        $file_array,
                        [
                            'width'  => $this->arParams['PICTURE_WIDTH'],
                            'height' => $this->arParams['PICTURE_HEIGHT'],

                        ]
                    )['src'];

                    $price = \CCatalogProduct::GetOptimalPrice(
                        $product['ID'],
                        1,
                        User::GetUserGroupArray(),
                        'N'
                    );
                    $products[$product['ID']]['DISCOUNT_PRICE'] = $price['RESULT_PRICE']['DISCOUNT_PRICE'];
                }

                cache_manager()->RegisterTag('catalog_product');

                cache_manager()->EndTagCache();

                return [
                    'products'      => $products,
                    'productsCount' => $count,
                    'pageSize'      => $this->arParams['PAGE_SIZE'],
                    'page_count'    => $page_count,
                    'page'          => $this->getPage(),
                ];
            },
            $cacheInitDir
        );

        /** @var \Bitrix\Sale\Basket $cart - Корзина пользователя */
        $cart = UserCart::getUserCart(new DefaultCartType());

        foreach ($cart->getBasketItems() as $item) {
            /** @var BasketItem $item */
            $productId = $item->getField('PRODUCT_ID');
            $productModel = $this->arResult['products'][$productId];
            if ($productModel) {
                $productModel->setInBasket(true);
            }
            $this->productIdsInCart[] = $productId;
        }

//        $this->arResult['currency'] = $currency;
//        $this->arResult['checkedProductsInfo'] = $this->getStashedForCartProductsInfo();
    }

    /**
     * Дополнительная подготовка параметров.
     *
     * @param $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams)
    {
        $this->loadParams();

        return parent::onPrepareComponentParams($arParams);
    }

    /**
     * Запускаем компонент
     *
     * @return void
     */
    public function executeComponent(): void
    {
        $this->loadData();
        $this->chooseTemplate(
            count($this->arResult['products']),
            $this->template,
            function (?string &$template) {
                if ($this->arParams['filter']) {
                    $template = '';
                } else {
                    $template = 'include/_products';
                }
            }
        );

        $this->arResult['view'] = $this->chooseView();
        $this->arResult['isShowMore'] = !!$this->isShowMore;
        $this->arResult['page'] = $this->getPage();


        $this->includeComponentTemplate($this->template);
    }


    /**
     * Получает топ продуктов по характеристикам
     */
    private function getTopProducts(): void
    {
        $this->rules = collect(self::$availableRules)
            ->map(
                static function ($className) {
                    return new $className();
                }
            )
            ->intersectByKeys(array_flip($this->arParams['RULES']));

        if ($this->rules->isNotEmpty()) {
            $this->applyRules();
        }
    }

    /**
     * Применяет заданные в параметрах правила и отбирает бриллианты.
     */
    private function applyRules(): void
    {
        $top = collect();
        $middle = collect();
        $other = collect();

        foreach ($this->rules as $rule) {
            $rule->setCount($this->arParams['COUNT_FOR_RULE'])
                ->apply(TTL::sec2min($this->arParams['CACHE_TIME']));

            if ($rule->getDiamonds()->isNotEmpty()) {
                $ruleProduct = $rule->getDiamonds()->shift();
                if (in_array($ruleProduct->getId(), $this->productIdsInCart)) {
                    $ruleProduct->setInBasket(true);
                }

                $first = $this->makeItem($rule, $ruleProduct);
                if ($top->count() < $this->arParams['INITIAL_COUNT']) {
                    $top->push($first);
                } else {
                    // Если правил больше чем выводится в начальном варианте списка, то
                    // остальные первые камни должны быть в начале оставшихся
                    $middle->push($first);
                }

                $other = $other->concat(
                    $rule->getDiamonds()->map(
                        function ($diamond) use ($rule) {
                            return $this->makeItem($rule, $diamond);
                        }
                    )
                );
            }
        }

        $this->arResult['top']['top'] = $top;
        $this->arResult['topProductsIds'] = $top->map(
            function ($item) {
                return $item['product']->getId();
            }
        );
        $this->arResult['top']['other'] = $middle->concat($other);
    }

    /**
     * Формирует данные о топовом элементе.
     *
     * @param AbstractTopFilterRule $rule
     * @param Catalog $product
     * @return array
     */
    private function makeItem(AbstractTopFilterRule $rule, Catalog $product): array
    {
        $product->setPhotos();

        return [
            'ruleName' => $rule->getName(),
            'product'  => $product
        ];
    }

    private function chooseView(): string
    {
        $defaultView = $this->arParams['IS_B2C'] ? 'cell' : 'table';

        return ($this->arParams['VIEW'] ?? '') !== '' ? $this->arParams['VIEW'] : $defaultView;
    }

    public static function fullPropName($propname)
    {
        return 'IBLOCK_ELEMENTS_ELEMENT_CATALOG_' . $propname;
    }

}

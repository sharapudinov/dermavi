<?php

namespace App\Core\Jewelry\Filter;

use App\Core\BitrixProperty\Property;
use App\Core\Catalog\FilterFields\JewelryFilterFields;
use App\Core\Currency\Currency;
use App\Core\Jewelry\Enum\FilterUrlEnum;
use App\Helpers\JewelryHelper;
use App\Helpers\PriceHelper;
use App\Models\Catalog\HL\CatalogShape;
use App\Models\Catalog\HL\Form;
use App\Models\Catalog\HL\StoneType;
use App\Models\Jewelry\Dicts\JewelryCollection;
use App\Models\Jewelry\Dicts\JewelryMetalColor;
use App\Models\Jewelry\Dicts\JewelrySex;
use App\Models\Jewelry\Dicts\JewelryStyle;
use App\Models\Jewelry\Dicts\JewelryType;
use App\Models\Jewelry\Jewelry;
use App\Models\Jewelry\JewelryDiamond;
use App\Models\Jewelry\JewelrySection;
use App\Models\Jewelry\JewelrySku;
use App\Provider\JewelryPropertyUniqD7Provider;
use App\Provider\JewelrySkuPropertyUniqD7Provider;
use Bitrix\Main\Db\SqlQueryException;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику работы фильтра для каталога ЮБИ
 * Class JewelryFilter
 *
 * @package App\Core\Jewelry\Filter
 */
class JewelryFilter extends FilterBaseAbstract
{
    /** @var JewelrySkuPropertyUniqD7Provider */
    protected $jewelrySkuPropertyProvider;

    /** @var JewelryPropertyUniqD7Provider */
    protected $jewelryPropertyProvider;

    /** @var bool Выводить только предложения для раздела Распродажа */
    protected $saleOffersMode = false;

    /**
     * @var bool Увеличивать ли правую границу диапазонных значений при их равенстве
     * После задачи ALRSUP-1332 (ALRSUP-1820) это больше не нужно делать в ювелирке
     */
    protected $expandEqualRange = false;

    /**
     * Возвращает уникальные данные о цвете металла для заданого подраздела
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return array
     */
    private function getMetalColorInfo()
    {
        $possibleMetalColor = $this->jewelrySkuPropertyProvider->getUniqPropertyValue('METAL_COLOR_ID');
        $metalColors        = $this->jewelrySkuPropertyProvider->getUniqPropertyValue(
            ['METAL_COLOR_ID', 'METAL_ID'],
            null,
            'DISTINCT CONCAT(%s,"#",%s)'
        );

        $metalColorPair = [];
        foreach ($metalColors as $metalColor) {
            [$color, $metal] = explode('#', $metalColor);
            if (!in_array($color, $possibleMetalColor)) {
                continue;
            }
            $metalColorPair[] = [
                'color' => $color,
                'metal' => $metal,
            ];
        }

        return JewelryMetalColor::getUniquePairs(collect($metalColorPair));
    }

    /**
     * Доступные формы кристаллов
     *
     * @throws SqlQueryException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return array
     */
    private function getShapes(): array
    {
        $arShapes = $this->jewelrySkuPropertyProvider->getUniqPropertyValue('DIAMOND_IDS');

        // Нет у активных оферов
        if (!$arShapes) {
            return [];
        }

        // Найдем уникальные XML_ID для catalog_shape, т.к. они никак не завязаны
        $sql = 'SELECT DISTINCT (f.UF_SHAPE_XML_ID) as shape '
            . 'FROM ' . Form::getTableName() . ' as f '
            . 'INNER JOIN ' . JewelryDiamond::getTableName() . ' as d '
            . 'ON d.UF_SHAPE_ID=f.ID '
            . 'WHERE d.UF_XML_ID IN (' . implode(',', $arShapes) . ');';

        $query           = db()->query($sql);
        $arShapesCatalog = [];

        while ($tempAr = $query->fetch()) {
            $arShapesCatalog[] = $tempAr['shape'];
        }

        // Нет таких для каталога бриллиантов
        if (!$arShapesCatalog) {
            return [];
        }

        $sql = 'SELECT DISTINCT CONCAT(ID, "#", UF_XML_ID, "#", UF_DISPLAY_VALUE_' . $this->language . ') as shapeDict '
            . 'FROM ' . CatalogShape::getTableName() . ' '
            . 'WHERE UF_XML_ID IN ("' . implode('","', $arShapesCatalog) . '");';

        $query  = db()->query($sql);
        $result = [];
        while ($tempAr = $query->fetch()) {
            [$id, $xmlId, $value] = explode('#', $tempAr['shapeDict']);
            $result[$id] = [
                'xml_id' => $xmlId,
                'value'  => $value,
            ];
        }

        return $result;
    }

    /**
     * Возвращает гендерную принадлежность украшений
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return array
     */
    private function getGenders()
    {
        $xmlIdList = $this->jewelrySkuPropertyProvider->getUniqPropertyValue('SEX_ID');
        if (!$xmlIdList) {
            return [];
        }

        return JewelrySex::filter(
            [
                '=UF_XML_ID' => $xmlIdList,
            ]
        )
                         ->select(
                             ['ID', 'UF_XML_ID', 'UF_NAME']
                         )->getList()
                         ->pluck('UF_XML_ID', 'UF_NAME')
                         ->toArray();
    }

    /**
     * Коллекции украшений
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return array
     */
    private function getCollectionJewelry(): array
    {
        $collectionId = $this->jewelryPropertyProvider->getUniqPropertyValue(
            'COLLECTION_ID'
        );
        if (!$collectionId) {
            return [];
        }

        $collections = JewelryCollection::filter(
            [
                '=UF_XML_ID' => $collectionId,
            ]
        )
                                        ->select(
                                            ['ID', 'UF_XML_ID', 'UF_NAME_' . $this->language]
                                        )->getList();

        $result      = [];

        //fixme необходимо просмотреть генерацию алиаса, т.к. xml_id на русском
        // и там есть одинаковые элементы с немного разным написанием
        /** @var JewelryCollection $collection */
        foreach ($collections as $collection) {
            $result[] = [
                'key'   => $collection->getExternalID(),
                'value' => $collection->getName($this->language),
            ];
        }

        return $result;
    }

    /**
     * События украшений
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return array
     */
    private function getTypesJewelry(): array
    {
        $xmlIdList = $this->jewelryPropertyProvider->getUniqPropertyValue('JEWELRY_TYPES');
        if (!$xmlIdList) {
            return [];
        }

        return JewelryType::filter(
            [
                '=UF_XML_ID' => $xmlIdList,
            ]
        )
                          ->select(
                              ['ID', 'UF_XML_ID', 'UF_NAME_' . $this->language]
                          )->getList()
                          ->pluck('UF_NAME_' . $this->language, 'UF_XML_ID')
                          ->toArray();
    }

    /**
     * Стили украшений
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return array
     */
    private function getStyleJewelry(): array
    {
        $xmlIdList = $this->jewelrySkuPropertyProvider->getUniqPropertyValue('STYLE_ID');
        if (!$xmlIdList) {
            return [];
        }

        return JewelryStyle::filter(
            [
                '=UF_XML_ID' => $xmlIdList,
            ]
        )
                           ->select(
                               ['ID', 'UF_XML_ID', 'UF_NAME_' . $this->language]
                           )->getList()
                           ->pluck('UF_NAME_' . $this->language, 'UF_XML_ID')
                           ->toArray();
    }

    /**
     * Типы камней
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return array
     */
    public function getStoneType(): array
    {
        $diamondsXmlId = $this->jewelrySkuPropertyProvider->getUniqPropertyValue('DIAMOND_IDS');
        if (!$diamondsXmlId) {
            return [];
        }

        /** @var Collection $diamonds */
        $diamondsGroupCollection = JewelryDiamond::cache($this->getCacheTTL())
                                                 ->filter(
                                                     [
                                                         '=UF_XML_ID' => $diamondsXmlId,
                                                         '!UF_TYPE'  => false,
                                                     ]
                                                 )
                                                 ->select(['ID', 'UF_TYPE'])
                                                 ->with('stoneType')
                                                 ->getList()->groupBy('UF_TYPE');

        $result = [];

        /** @var Collection $diamonds */
        foreach ($diamondsGroupCollection as $diamonds) {
            /** @var  JewelryDiamond $diamond */
            $diamond                     = $diamonds->first();
            $stoneType                   = $diamond->stoneType;
            // ARSUP-959 Добавил проверку на существование типа камня
            if ($stoneType) {
                $result[$stoneType->getId()] = $stoneType->getName();
            }
        }

        return $result;
    }

    /**
     * @return string[]
     */
    protected function getCacheTags(): array
    {
        return [
            'catalog_jewelry',
            'iblock_id_' . JewelrySku::iblockID(),
            'iblock_id_' . Jewelry::iblockID(),
        ];
    }

    /**
     * Возвращает уникальную информацию для фильтра по типу
     *
     * @return array|mixed[]
     * @throws Missing404Exception
     * @throws SqlQueryException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected function getUniqueInfo(): array
    {
        /** @var JewelrySection $section */
        $section = $this->sectionCode ? JewelryHelper::getSectionModelByCode($this->sectionCode, ['types']) : null;
        if (!$section && $this->isSectionRequired()) {
            throw new Missing404Exception('Section not found');
        }

        // prepareFilter не умеет получать id вариантов в свойствах связанных таблиц,
        // поэтому используем этот способ везде
        $sellingAvailableEnumId = Property::getListPropertyValue(
            JewelrySku::iblockID(),
            'SELLING_AVAILABLE',
            'Y'
        )->getVariantId();

        /**
         * Провайдер для таблицы свойств инфоблока торговых предложений
         */
        $addFilter = [
            '=SELLING_AVAILABLE' => $sellingAvailableEnumId,
        ];
        if ($section) {
            // С первого взгляда может показаться, что REF_IBLOCK_ELEMENT -
            // это связь с элементом торгового предложения, но нет, это связь
            // с элементом товара, а торговое предложение - REF_PRODUCT
            $addFilter['=REF_IBLOCK_ELEMENT.IBLOCK_SECTION_ID'] = $section->getId();
        }

        // Исключение/включение предложений по распродаже (ALRSUP-1332)
        if ($this->isSaleOffersMode()) {
            $addFilter = JewelryFilterFields::getSaleOfferInclusionFilterD7Provider($addFilter);
        } else {
            $addFilter = JewelryFilterFields::getSaleOfferExclusionFilterD7Provider($addFilter);
        }

        $this->jewelrySkuPropertyProvider =
            JewelrySkuPropertyUniqD7Provider::init()
                ->setCacheTTL($this->getCacheTTL())
                ->setFilter(array_merge($this->filter, $addFilter));

        /**
         * Провайдер для таблицы свойств инфоблока товаров
         *
         * Внимание!
         * Если фильтры по свойствам предложений будут убираться, то нужно убрать также и статические Reference
         * SKU_PROPERTY и SKU_ELEMENT (см. JewelryPropertyUniqD7Provider::getReference()),
         * чтобы в запросе не делались лишние джойны
         */
        $addFilter = [
            '=SKU_PROPERTY.SELLING_AVAILABLE' => $sellingAvailableEnumId,
            // Добавляем также проверку элементов свойств предложений,
            // Добавляем также проверку элементов свойств предложений,
            // чтобы они были активны и не являлись записями документооборота
            '=SKU_ELEMENT.ACTIVE' => 'Y',
            '=SKU_ELEMENT.WF_PARENT_ELEMENT_ID' => false,
            // Эти фильтры автоматически добавляются в getFilter()
            //'=REF_IBLOCK_ELEMENT.ACTIVE' => 'Y',
            //'=REF_IBLOCK_ELEMENT.WF_PARENT_ELEMENT_ID' => false,
        ];
        if ($section) {
            $addFilter['=REF_IBLOCK_ELEMENT.IBLOCK_SECTION_ID'] = $section->getId();
        }

        // Исключение/включение предложений по распродаже (ALRSUP-1332)
        if ($this->isSaleOffersMode()) {
            $addFilter = JewelryFilterFields::getSaleOfferInclusionFilterD7Provider($addFilter, 'SKU_PROPERTY.');
        } else {
            $addFilter = JewelryFilterFields::getSaleOfferExclusionFilterD7Provider($addFilter, 'SKU_PROPERTY.');
        }

        $this->jewelryPropertyProvider =
            JewelryPropertyUniqD7Provider::init()
                ->setCacheTTL($this->getCacheTTL())
                ->setFilter(array_merge($this->filter, $addFilter));

        /**
         * Цена
         */
        $prices = $this->jewelrySkuPropertyProvider->getRangePropertyValue('FINAL_PRICE', 'float');
        foreach ($prices as &$price) {
            $price = (int)PriceHelper::getPriceInSpecificCurrency(
                $price,
                Currency::getCurrentCurrency()
            );
        }
        unset($price);

        /**
         * Размеры
         */
        $sizes = $this->jewelrySkuPropertyProvider->getUniqPropertyValue('SIZE', 'float');

        /**
         * Масса карат
         */
        $weight = $this->jewelrySkuPropertyProvider->getRangePropertyValue('WEIGHT_SUM', 'float');

        /**
         * Количество бриллиантов
         */
        $diamondsCount = $this->jewelrySkuPropertyProvider->getRangePropertyValue('DIAMONDS_COUNT', 'int');

        return [
            FilterUrlEnum::SIZES          => $sizes,
            'section'                     => $section,
            FilterUrlEnum::PRICE          => [
                'min' => $prices['MIN'],
                'max' => $prices['MAX'],
            ],
            FilterUrlEnum::STYLES         => ($tmpFilter = $this->getStyleJewelry())
                ? JewelryStyle::getTransformFilterToUrl($tmpFilter)
                : [],
            FilterUrlEnum::WEIGHT         => [
                'min' => $weight['MIN'],
                'max' => $weight['MAX'],
            ],
            'weightName'                  => 'Масса бриллиантов, карат',
            'priceTitle'                  => 'Цена',
            FilterUrlEnum::COLLECTIONS    => ($tmpFilter = $this->getCollectionJewelry())
                ? JewelryCollection::getTransformFilterToUrl($tmpFilter)
                : [],
            FilterUrlEnum::DIAMONDS_COUNT => [
                'minimum' => $diamondsCount['MIN'],
                'maximum' => $diamondsCount['MAX'],
            ],
            FilterUrlEnum::METALS_COLORS  => ($tmpFilter = $this->getMetalColorInfo())
                ? JewelryMetalColor::getTransformFilterToUrl($tmpFilter)
                : [],
            'types'                       => $section->types ?? new Collection(),
            'popupClass'                  => 'filters--jewelry popup popup--filters',
            'ringsType'                   => $section && $section->getCode() === 'rings',
            FilterUrlEnum::SHAPES         => $this->getShapes(),
            FilterUrlEnum::GENDERS        => ($tmpFilter = $this->getGenders())
                ? JewelrySex::getTransformFilterToUrl($tmpFilter)
                : [],
            FilterUrlEnum::EVENTS         => ($tmpFilter = $this->getTypesJewelry())
                ? JewelryType::getTransformFilterToUrl($tmpFilter)
                : [],
            FilterUrlEnum::STONE_TYPE     => ($tmpFilter = $this->getStoneType())
                ? StoneType::getTransformFilterToUrl($tmpFilter)
                : [],
        ];
    }

    /**
     * @return bool
     */
    public function isSaleOffersMode(): bool
    {
        return $this->saleOffersMode;
    }

    /**
     * @param bool $saleOffersMode
     * @return static
     */
    public function setSaleOffersMode(bool $saleOffersMode)
    {
        $this->saleOffersMode = $saleOffersMode;

        return $this;
    }

    /**
     * @return string
     */
    protected function getCacheKey(): string
    {
        return parent::getCacheKey() . '|' . (int)$this->isSaleOffersMode();
    }
}

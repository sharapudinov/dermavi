<?php

namespace App\Core\Jewelry\Filter;

use App\Core\Currency\Currency;
use App\Core\Jewelry\Enum\FilterUrlEnum;
use App\Helpers\JewelryHelper;
use App\Helpers\PriceHelper;
use App\Models\Catalog\Diamond;
use App\Models\Catalog\HL\Form;
use App\Models\Jewelry\Dicts\JewelryCollection;
use App\Models\Jewelry\Dicts\JewelryFamily;
use App\Models\Jewelry\Dicts\JewelryMetalColor;
use App\Models\Jewelry\HL\JewelryBlankDiamonds;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Db\SqlQueryException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Support\Collection;
use App\Provider\JewelryBlankPropertyUniqD7Provider;
use App\Provider\JewelryBlankSkuPropertyUniqD7Provider;
use App\Helpers\TTL;

/**
 * Класс, описывающий логику работы фильтра оправ для конструктора ЮБИ
 * Class FramesFilterORM
 *
 * @package App\Core\Jewelry\Filter
 */
class FramesFilterORM extends FilterBaseAbstract
{
    /** @var Diamond|null */
    protected $diamond = null;

    /** @var JewelryBlankSkuPropertyUniqD7Provider */
    protected $jewelryBlankSkuPropertyProvider;

    /** @var JewelryBlankPropertyUniqD7Provider */
    protected $jewelryBlankPropertyProvider;

    /** @var JewelryFamily|null $jewelryFamily Семейство изделий, выполняет роль разделов в конструкторе */
    protected $jewelryFamily;

    protected $jewelryBlankPropertyFilter;

    protected $jewelryBlankSkuPropertyFilter;

    /**
     * FramesFilter constructor.
     *
     * @param string $sectionCode
     */
    public function __construct(string $sectionCode)
    {
        parent::__construct($sectionCode);

        if ($diamondId = e($_REQUEST['diamondId'])) {
            $this->diamond = Diamond::getById($diamondId);
        }
    }

    /**
     * Возвращает уникальную информацию для фильтра по типу
     *
     * @return array|mixed[]
     *
     * @throws Missing404Exception
     * @throws SqlQueryException
     */
    protected function getUniqueInfo(): array
    {
        $this->jewelryBlankPropertyFilter = $this->filter;
        $this->jewelryBlankSkuPropertyFilter = $this->filter;

        // Отсекаем промежуточные версии ТОВАРА
        $this->jewelryBlankPropertyFilter['=REF_IBLOCK_ELEMENT.WF_PARENT_ELEMENT_ID'] = false;

        // Отсекаем промежуточные версии ТОВАРНОГО ПРЕДЛОЖЕНИЯ
        $this->jewelryBlankSkuPropertyFilter['=REF_PRODUCT.WF_PARENT_ELEMENT_ID'] = false;

        try {
            /** @var JewelryFamily Семейство изделий, выполняет роль разделов в конструкторе */
            $this->jewelryFamily = $this->getJewelryFamily();
        } catch (Missing404Exception $e) {
            show404();
        }

        /** @var array|int[] Массив идентификаторов оправ, которым доступны бриллианты */
        $frames = JewelryHelper::getCombinationFrames(!$this->jewelryFamily);

        // При наличии семейства изделий, учитываем его при фильтрации
        if ($this->jewelryFamily) {
            $this->jewelryBlankPropertyFilter['=JEWELRY_FAMILY'] = $this->jewelryFamily->getExternalID();
            // todo придумать более элегантный способ фильтрации по семейству
            // Для товарного предложения не получается достучаться напрямую до семейства,
            // поэтому фильтруем по ID товаров принадлежащих конкретному семейству
            $jewelryFamilyFrames = $this->jewelryFamily->frames ? $this->jewelryFamily->frames->pluck(
                'ID'
            )->toArray() : [];
            $this->jewelryBlankSkuPropertyFilter['=REF_IBLOCK_ELEMENT.ID'] = $jewelryFamilyFrames;
        }

        // При наличии конкретных идентификаторов оправ, учитываем их при фильтрации
        if ($frames) {
            $this->jewelryBlankPropertyFilter['=REF_IBLOCK_ELEMENT.ID'] = $frames;
        }

        // При наличии Id бриллианта ищем оправы, подходящие под него
        if ($this->diamond) {
            $jewelryIds = $this->getJewelryBlankByDiamond();

            if (!empty($jewelryIds)) {
                $this->jewelryBlankPropertyFilter['=REF_IBLOCK_ELEMENT.ID'] = $jewelryIds;
                $this->jewelryBlankSkuPropertyFilter['=REF_IBLOCK_ELEMENT.ID'] = $jewelryIds;
            }
        }

        $this->jewelryBlankSkuPropertyProvider =
            JewelryBlankSkuPropertyUniqD7Provider::init()
                                                 ->setCacheTTL($this->getCacheTTL())
                                                 ->setFilter(
                                                     $this->jewelryBlankSkuPropertyFilter
                                                 );

        $this->jewelryBlankPropertyProvider =
            JewelryBlankPropertyUniqD7Provider::init()
                                              ->setCacheTTL($this->getCacheTTL())
                                              ->setFilter(
                                                  $this->jewelryBlankPropertyFilter
                                              );

        /**
         * Размеры
         */
        $sizes = $this->jewelryBlankSkuPropertyProvider->getUniqPropertyValue('SIZE', 'float');

        /**
         * Цена
         */
        $prices = $this->jewelryBlankPropertyProvider->getRangePropertyValue('PRICE', 'float');
        foreach ($prices as &$price) {
            $price = (int)PriceHelper::getPriceInSpecificCurrency(
                $price,
                Currency::getCurrentCurrency()
            );
        }
        unset($price);

        /**
         * Количество бриллиантов
         */
        // Временно скрыл фильтр по кол-ву бриллиантов. Будет возвращен в отдельной задаче.
        //$diamondsCount = $this->jewelryBlankPropertyProvider->getRangePropertyValue('DIAMONDS_COUNT', 'int');

        // Хак для слайдера на фронте
        //if ($diamondsCount['MAX'] == $diamondsCount['MIN']) {
        //    $diamondsCount['MAX'] = $diamondsCount['MIN'] + 1;
        //}

        /**
         * Масса карат
         */
        $weight = $this->jewelryBlankPropertyProvider->getRangePropertyValue('WEIGHT', 'float');

        /**
         * Форма брилилантов
         */
        $shapes = $this->getShapes();

        return [
            FilterUrlEnum::SIZES          => $sizes,
            FilterUrlEnum::PRICE          => [
                'min' => $prices['MIN'],
                'max' => $prices['MAX'],
            ],
            // Временно скрыл фильтр по кол-ву бриллиантов. Будет возвращен в отдельной задаче.
            /*
            FilterUrlEnum::DIAMONDS_COUNT => [
                'minimum' => $diamondsCount['MIN'],
                'maximum' => $diamondsCount['MAX'],
            ],
            */
            FilterUrlEnum::WEIGHT => [
                'min' => $weight['MIN'],
                'max' => $weight['MAX'],
            ],
            'weightName'                  => 'Вес оправы, г',
            FilterUrlEnum::METALS_COLORS  => ($tmpFilter = $this->getMetalColorInfo())
                ? JewelryMetalColor::getTransformFilterToUrl($tmpFilter)
                : [],
            'types'                       => $this->jewelryFamily ? $this->jewelryFamily->types : new Collection(),
            'popupClass'                  => 'filters--jewelry popup popup--filters',
            // Т.к. фильтрация по коллекциям пока не требуется, закомментировал строки ниже
            /*
            FilterUrlEnum::COLLECTIONS    => ($tmpFilter = $this->getCollectionJewelry())
                ? JewelryCollection::getTransformFilterToUrl($tmpFilter)
                : [],
            */
            'ringsType'                   => $this->jewelryFamily && $this->jewelryFamily->getCode() === 'kolco',
            'priceTitle'                  => 'Цена за оправу',
            FilterUrlEnum::SHAPES_BLANK   => $shapes,
        ];
    }

    /**
     * @return string
     */
    protected function getCacheKey(): string
    {
        $key = parent::getCacheKey();
        if ($this->diamond) {
            $key .= '_' . $this->diamond->getShapeID();
        }

        return $key;
    }

    /**
     * Возвращает уникальные данные о цвете металла для заданого подраздела
     *
     * @return array
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    private function getMetalColorInfo()
    {
        $possibleMetalColor = $this->jewelryBlankSkuPropertyProvider->getUniqPropertyValue('METAL_COLOR_ID');
        $metalColors        = $this->jewelryBlankSkuPropertyProvider->getUniqPropertyValue(
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
     * @return JewelryFamily|null
     * @throws Missing404Exception
     */
    protected function getJewelryFamily()
    {
        $jewelryFamily = null;
        if ($this->sectionCode && $this->sectionCode != 'all') {
            $jewelryFamily = JewelryHelper::getJewelryFamilyByCode(e($this->sectionCode), ['types']);
            if (!$jewelryFamily) {
                throw new Missing404Exception('Family not found');
            }
        }

        return $jewelryFamily;
    }

    /**
     * Доступные формы кристаллов
     *
     * @return array
     */
    private function getShapes(): array
    {
        $arShapes = $this->jewelryBlankPropertyProvider->getUniqPropertyValue('SHAPE', 'int');

        if (!$arShapes) {
            return [];
        }

        $shapes = Form::filter(
            [
                'UF_XML_ID' => $arShapes,
            ]
        )->select(
            [
                'ID',
                'UF_XML_ID',
                'UF_SHAPE_XML_ID',
                'UF_DISPLAY_VALUE_' . $this->language,
            ]
        )->cache(
            TTL::WEEK
        )->getList();

        $result = [];

        foreach ($shapes as $shape) {
            $result[$shape['UF_XML_ID']] = [
                'xml_id' => $shape['UF_SHAPE_XML_ID'],
                'value'  => $shape['UF_DISPLAY_VALUE_' . $this->language],
            ];
        }

        return $result;
    }

    /**
     * Коллекции украшений
     *
     * @return array
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    private function getCollectionJewelry(): array
    {
        $collectionId = $this->jewelryBlankPropertyProvider->getUniqPropertyValue(
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

        $result = [];

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
     * Возвращает оправы подходящие под конкретный брилилант
     *
     * @return array
     */
    private function getJewelryBlankByDiamond(): array
    {
        $jewelryIds = [];

        $blankDiamonds = JewelryBlankDiamonds::filter(
            [
                'UF_DIAMONDS_ID' => $this->diamond->getID(),
            ]
        )->getList();

        if ($blankDiamonds->isEmpty()) {
            return $jewelryIds;
        }

        foreach ($blankDiamonds as $group) {
            if (in_array($this->diamond->getID(), $group->getDiamondsIds())) {
                $jewelryIds[] = $group->getBlankId();
            }
        }

        return array_unique($jewelryIds);
    }
}

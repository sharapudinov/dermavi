<?php

namespace App\Core\Jewelry\Enum;

use App\Models\Catalog\HL\StoneType;
use App\Models\Jewelry\Dicts\JewelryBlankShapes;
use App\Models\Jewelry\Dicts\JewelryCollection;
use App\Models\Jewelry\Dicts\JewelryMetalColor;
use App\Models\Jewelry\Dicts\JewelrySex;
use App\Models\Jewelry\Dicts\JewelryShapes;
use App\Models\Jewelry\Dicts\JewelryStyle;
use App\Models\Jewelry\Dicts\JewelryType;

/**
 * Справочник констант для фильтра
 *
 * Class FilterUrlEnum
 * @package App\Core\Jewelry\Enum
 */
class FilterUrlEnum
{
    public const FILTER_PARAMS_DELIMETR = '_or_'; // делиметр параметров фильтра
    public const FILTER_TYPE_DELIMETR   = '--'; // делиметр типа фильтра
    public const FILTER_KEY             = 'filter'; // ключ, указывающий на наличие фильтра в url

    public const METALS         = 'metals'; // todo вроде как ни где не участвует
    public const GENDERS        = 'genders'; // гендерная принадлежность
    public const SHAPES         = 'shapes'; // форма брилианта
    public const SHAPES_BLANK   = 'blank_shapes'; // форма брилианта для конструктора
    public const COLLECTIONS    = 'collections'; // коллекция украшений
    public const METALS_COLORS  = 'metal_colors'; // цвет металла
    public const STYLES         = 'styles'; // стили украшений
    public const PRICE          = 'price'; // цена
    public const WEIGHT         = 'weight'; // масса бриллиантов
    public const DIAMONDS_COUNT = 'diamonds_count'; //кол-во бриллиантов
    public const SIZES          = 'sizes'; //размеры
    public const PRICE_CARAT    = 'price_carat'; //цена за карат
    public const EVENTS         = 'events'; // к событию
    public const STONE_TYPE     = 'stoneType'; // к событию

    /**
     * Фильры, которые требуют трансформации url
     * и участвуют в сео
     */
    public const FILTER_URL_ENUM = [
        self::GENDERS       => JewelrySex::class,
        self::METALS_COLORS => JewelryMetalColor::class,
        self::SHAPES        => JewelryShapes::class,
        self::SHAPES_BLANK  => JewelryBlankShapes::class,
        self::COLLECTIONS   => JewelryCollection::class,
        self::STYLES        => JewelryStyle::class,
        self::EVENTS        => JewelryType::class,
        self::STONE_TYPE    => StoneType::class,
    ];

    /**
     * Фильтры с диапазоном значений
     */
    public const FILTER_RANGE_URL_ENUM = [
        self::PRICE,
        self::WEIGHT,
        self::DIAMONDS_COUNT,
    ];

    /**
     * Порядок приоритета фильтров
     */
    public const FILTER_PRIORITY_NUM = [
        self::STYLES         => 100,
        self::SIZES          => 200,
        self::METALS_COLORS  => 300,
        self::SHAPES         => 400,
        self::SHAPES_BLANK   => 400,
        self::STONE_TYPE     => 450,
        self::GENDERS        => 500,
        self::COLLECTIONS    => 700,
        self::PRICE          => 800,
        self::DIAMONDS_COUNT => 900,
        self::WEIGHT         => 1000,
        self::EVENTS         => 1100,
    ];
}

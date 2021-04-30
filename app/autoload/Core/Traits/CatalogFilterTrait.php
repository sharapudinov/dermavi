<?php

namespace App\Core\Traits;

use App\Core\Catalog\DetailFilterRules\DetailFilterRuleInterface;
use App\Core\Currency\Currency;
use App\Helpers\LanguageHelper;
use App\Helpers\NumberHelper;
use App\Helpers\PriceHelper;
use App\Helpers\StringHelper;
use App\Helpers\TTL;
use App\Helpers\UserHelper;
use App\Models\Catalog\Catalog;
use App\Models\Catalog\HL\CatalogClarity;
use App\Models\Catalog\HL\CatalogFluorescence;
use App\Models\Catalog\HL\CatalogShape;
use App\Models\Catalog\HL\Culet;
use App\Models\Catalog\HL\Polish;
use App\Models\Catalog\HL\Quality;
use App\Models\Catalog\HL\StoneLocation;
use App\Models\Catalog\HL\Symmetry;
use App\Provider\DiamondPropertyProvider;

/**
 * Класс-трейт для работы с фильтром каталога
 * Trait CatalogFilterTrait
 * @package App\Core
 */
trait CatalogFilterTrait
{

    /** @var string|null $sectionCode - Символьный код раздела, в котором находится пользователь (b2b, b2c, аукционы) */
    private $sectionCode;

    /**
     * Записывает в свойство класса символьный код раздела, в котором находится пользователь
     *
     * @param string $sectionCode - Символьный код раздела, в котором находится пользователь (b2b, b2c, аукционы)
     *
     * @return void
     */
    protected function setSectionCode(string $sectionCode): void
    {
        $this->sectionCode = $sectionCode;
    }

    /**
     * Формируем параметры фильтра
     *
     * @param bool $withDiameter Необходимость поиска бриллиантов с ненулевым диаметром
     *
     * @return array
     */
    protected function getFilterParameters(bool $withDiameter = false): array
    {
        /** @var string $languageVersion - Языковая версия сайта */
        $languageVersion = LanguageHelper::getLanguageVersion();

        /** @var \App\Core\Currency\Entity\CurrencyEntity $currency - Текущая валюта */
        $currency = Currency::getCurrentCurrency();

        /** @var string $cacheKey - Ключ для кеша */
        $cacheKey = get_class_name_without_namespace(self::class)
            . '_' . ($this->sectionCode ?? 'no_section')
            . '_' . $languageVersion
            . '_' . $withDiameter
            . '_' . $currency->getSymCode();

        return cache(
            $cacheKey,
            TTL::DAY,
            function () use ($languageVersion, $withDiameter) {

                // Провайдер св-в diamonds
                $propertyProvider = DiamondPropertyProvider::init($this->sectionCode);

                // Формы бриллиантов
                $shapes = CatalogShape::filter(
                    [
                        'UF_XML_ID' => $propertyProvider->getUniqPropertyXmlId('SHAPE'),
                    ]
                )->getList();

                // Вес бриллианта в каратах
                $carat = $propertyProvider->getRangeProperty('WEIGHT');

                // Огранки бриллиантов
                $qualityGroups = Quality::select('*')->filter(
                    [
                        'UF_XML_ID' => $propertyProvider->getUniqPropertyXmlId('CUT'),
                    ]
                )->getList();

                // Места рождений бриллиантов
                $origins = StoneLocation::select(
                    'ID',
                    'UF_XML_ID',
                    'UF_NAME_EN',
                    'UF_NAME_' . $languageVersion
                )->filter(
                    [
                        '!UF_LAT'   => null,
                        '!UF_LON'   => null,
                        'UF_XML_ID' => $propertyProvider->getUniqPropertyXmlId('ORIGIN'),
                    ]
                )->getList();

                // Годы добычи бриллиантов
                $yearsOfMining = collect(
                    $propertyProvider->getUniqPropertyXmlId('YEAR_MINING')
                )->sort();

                // Ввозрастов алмазов (млн лет)
                $ages = collect($propertyProvider->getUniqPropertyXmlId('AGE'))
                    ->map(
                        function ($age) {
                            return (int)$age;
                        }
                    )->sort();

                // Цена бриллианта
                $prices = $propertyProvider->getRangeProperty('PRICE');

                // Цена за карат в текущей валюте
                $pricesCarat = $propertyProvider->getRangeProperty('PRICE_CARAT');

                // Флюористенции бриллиантов
                $fluorescences = CatalogFluorescence::filter(
                    [
                        'UF_XML_ID' => $propertyProvider->getUniqPropertyXmlId('FLUOR'),
                    ]
                )->sort('UF_SORT', 'ASC')->getList();

                // Полировки бриллиантов */
                $polishes = Polish::filter(
                    [
                        '!UF_NAME'  => 'None',
                        'UF_XML_ID' => $propertyProvider->getUniqPropertyXmlId('POLISH'),
                    ]
                )->getList();

                // Симметрии бриллиантов
                $symmetries = Symmetry::filter(
                    [
                        '!UF_NAME'  => 'None',
                        'UF_XML_ID' => $propertyProvider->getUniqPropertyXmlId('SYMMETRY'),
                    ]
                )->getList();

                // Кулеты бриллиантов
                $culets = Culet::filter(
                    [
                        'UF_XML_ID' => $propertyProvider->getUniqPropertyXmlId('CULET'),
                    ]
                )->getList();

                return [
                    'shapes'          => $shapes,
                    'carats'          => [
                        'min' => (float)$carat['MIN'],
                        'max' => (float)$carat['MAX'],
                    ],
                    'colors'          => $propertyProvider->getUniqPropertyXmlId('COLOR'),
                    'quality_groups'  => $qualityGroups,
                    'origins'         => $origins,
                    'years_of_mining' => $yearsOfMining,
                    'prices'          => [
                        'max'                 => $this->recalculatePrice((float)$prices['MAX']),
                        'min'                 => $this->recalculatePrice((float)$prices['MIN']),
                        'price_per_carat_max' => $this->recalculatePrice(
                            (float)$pricesCarat['MAX']
                        ),
                        'price_per_carat_min' => $this->recalculatePrice(
                            (float)$pricesCarat['MIN']
                        ),

                    ],
                    'fluorescences'   => $fluorescences,
                    'polishes'        => $polishes,
                    'symmetries'      => $symmetries,
                    'culets'          => $culets,
                    'ages'            => $ages,
                    'clarity'         => $propertyProvider->getUniqPropertyXmlId('CLARITY'),
                ];
            }
        );
    }

    /**
     * Перерасчет цены в текущей валюте
     *
     * @param float $price
     *
     * @return float
     */
    protected function recalculatePrice(float $price, ?string $currency = null): float
    {
        if (!UserHelper::isLegalEntity()) {
            $price = PriceHelper::calculateWithTax((float)$price);
        }

        return PriceHelper::getPriceInCurrentCurrency($price, $currency);
    }

    /**
     * Получаем первоначальные данные о товаре для фильтра, которые подставятся при загрузке компонента
     *
     * @param Diamond     $product   - Товар
     * @param string|null $ruleClass - Класс с правилом работы фильтра
     *
     * @return array|mixed[]
     */
    protected function getFilterDefaultParametersForProduct(Diamond $product, ?string $ruleClass): array
    {
        $cacheKey = get_class_name_without_namespace(self::class) . '_default_parameters_for_product_'
            . $product->getID() . LanguageHelper::getLanguageVersion();

        return cache(
            $cacheKey,
            TTL::DAY,
            function () use ($product, $ruleClass) {
                /** @var int|null $caratsPolished - Количество карат в тысячах, которые обработал огранщик */
                $caratsPolished = null;
                if ($product->polisher) {
                    $caratsPolished = NumberHelper::transformNumberToThousands($product->polisher->getSummaryCarats());
                }

                /** @var string $colorDisplayValue - Полное название цвета */
                $colorDisplayValue = $product->getDiamondPacketColorValue();
                if (!$colorDisplayValue) {
                    $colorDisplayValue = $product->color->getDisplayValue();
                }

                $defaultFilterParameters = [
                    'shape'         => $product->shape,
                    'color'         => [
                        'model'         => $product->color,
                        'display_value' => $colorDisplayValue,
                    ],
                    'intensity'     => $product->diamondPacket ? $product->diamondPacket->intensity : null,
                    'clarity'       => $product->clarity,
                    'cut'           => $product->cut,
                    'fluorescence'  => $product->fluorescence,
                    'origin'        => $product->origin,
                    'polish'        => $product->polish,
                    'symmetry'      => $product->symmetry,
                    'culet'         => $product->culet,
                    'stone'         => $product->diamondPacket ? $product->diamondPacket->packetAdditionalInfo : null,
                    'polisher_info' => [
                        'polisher'       => $product->polisher,
                        'summary_carats' => $caratsPolished,
                        'polished_city'  => $product->diamondPacket
                            ? StringHelper::cityDeclension($product->diamondPacket->getPolishCityTracing())
                            : null,
                    ],
                ];

                if ($ruleClass && class_exists($ruleClass)) {
                    /** @var DetailFilterRuleInterface $ruleClass */
                    $ruleClass = new $ruleClass;

                    return $ruleClass->applyRule($product, $defaultFilterParameters);
                } else {
                    return $defaultFilterParameters;
                }
            }
        );
    }
}

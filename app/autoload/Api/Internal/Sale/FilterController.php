<?php

namespace App\Api\Internal\Sale;

use App\Api\BaseController;
use App\Core\Catalog\FilterFields\ProductFilterFields;
use App\Helpers\LanguageHelper;
use App\Helpers\PriceHelper;
use App\Helpers\StringHelper;
use App\Models\Catalog\Catalog;
use App\Models\Catalog\HL\Quality;
use App\Models\Catalog\HL\StoneLocation;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Класс-контроллер для работы с фильтрами
 * Class FilterController
 * @package App\Api\Internal\Sale
 */
class FilterController extends BaseController
{
    /**
     * Выполняем поиск бриллиантов из карточки бриллианта
     *
     * @return ResponseInterface
     */
    public function searchFromDiamondDetail(): ResponseInterface
    {
        try {
            /** @var string $languageVersion - Языковая версия сайта */
            $languageVersion = strtoupper(LanguageHelper::getLanguageVersion());

            /** @var int $shapes - идентификатор текущего бриллианта */
            $currentProductId = $this->getParam('product_id') ?? 0;

            /** @var array $shapes - Массив выбранных форм бриллиантов */
            $shapes = htmlentities_on_array($this->getParam('shape') ?? []);

            /** @var array $carat - Массив выбранного диапазона веса бриллиантов */
            $carat = $this->getParam('carat');

            /** @var array $colors - Массив выбранных цветов бриллиантов */
            $colors = htmlentities_on_array($this->getParam('color') ?? []);

            /** @var array $clarities - Массив выбранных прозрачностей бриллиантов */
            $clarities = htmlentities_on_array($this->getParam('clarity') ?? []);

            /** @var array $cutsFromForm - Массив выбранных в форме огранок бриллиантов */
            $cutsFromForm = htmlentities_on_array($this->getParam('cut') ?? []);
            $cutsQuery = Quality::filter(['UF_DISPLAY_VALUE_' . $languageVersion => $cutsFromForm])
                ->getList();
            /** @var array $cuts - Массив внешних кодов выбранных огранок */
            $cuts = [];
            foreach ($cutsQuery as $cut) {
                $cuts[] = $cut->getExternalID();
            }

            /** @var array $originsFromForm - Массив выбранных в форме месторождений */
            $originsFromForm = htmlentities_on_array($this->getParam('origin') ?? []);
            $originsQuery = StoneLocation::filter(['UF_NAME_' . $languageVersion => $originsFromForm])
                ->getList();
            /** @var array $origins - Массив внешних кодов выбранных месторождений */
            $origins = [];
            foreach ($originsQuery as $origin) {
                $origins[] = $origin->getXmlId();
            }

            /** @var array $yearsOfMining - Массив выбранных годов добычи */
            $yearsOfMining = htmlentities_on_array($this->getParam('year_mining') ?? []);

            /** @var array $fluorescences - Массив выбранных флюоресценций */
            $fluorescences = htmlentities_on_array($this->getParam('fluorescence') ?? []);

            /** @var array $culets - Массив выбранных кулетов */
            $culets = htmlentities_on_array($this->getParam('culet') ?? []);

            /** @var array $polishes - Массив выбранных полировок */
            $polishes = htmlentities_on_array($this->getParam('polish') ?? []);

            /** @var array $symmetries - Массив выбранных симметрий */
            $symmetries = htmlentities_on_array($this->getParam('symmetry') ?? []);

            /** @var array $price - Массив выбранного диапазона цен бриллиантов за карат */
            $price = $this->getParam('price');
            $priceFrom = PriceHelper::getPriceInDefaultCurrency(
                (float)StringHelper::cutAllSymbolsFromNumber(e($price['from']))
            );
            $priceTo = PriceHelper::getPriceInDefaultCurrency(
                (float)StringHelper::cutAllSymbolsFromNumber(e($price['to']))
            );

            /** @var array $ages - Массив возрастов алмаза (млн лет) */
            $ages = htmlentities_on_array($this->getParam('age') ?? []);

            /** @var array $filter - Фильтр для каталога товаров */
            $filter = [
                'ACTIVE' => 'Y',
                'PROPERTY_SHAPE' => $shapes,
                '>=PROPERTY_WEIGHT' => e($carat['from']),
                [
                    'LOGIC' => 'AND',
                    ['>=PROPERTY_WEIGHT' => e($carat['from'])],
                    ['<=PROPERTY_WEIGHT' => e($carat['to'])],
                ],
                'PROPERTY_COLOR' => $colors,
                'PROPERTY_CLARITY' => $clarities,
                'PROPERTY_CUT' => $cuts,
                'PROPERTY_ORIGIN' => $origins,
                'PROPERTY_YEAR_MINING' => $yearsOfMining,
                [
                    'LOGIC' => 'AND',
                    ['>=PROPERTY_PRICE' => $priceFrom],
                    ['<=PROPERTY_PRICE' => $priceTo],
                ],
                'PROPERTY_FLUOR' => $fluorescences,
                'PROPERTY_CULET' => $culets,
                'PROPERTY_POLISH' => $polishes,
                'PROPERTY_SYMMETRY' => $symmetries,
                'PROPERTY_AGE' => $ages,
                'PROPERTY_SELLING_AVAILABLE_VALUE' => 'Y',
            ];
            if ($currentProductId) {
                $filter['!ID'] = $currentProductId;
            }

            /** @var \Illuminate\Support\Collection $diamonds - Коллекция найденных бриллиантов */
            $diamonds = Diamond::filter($filter)->getList();

            /** @var string $filteredCatalogUri - Формируем ссылку на отфильтрованный каталог */
            $filteredCatalogUri = get_language_version_href_prefix() . '/diamonds/?enter=true';
            if ($diamonds->isNotEmpty()) {
                /** @var array $filterProperties - Массив свойств фильтра */
                $filterProperties = (new ProductFilterFields())->getFilter();
                foreach ($filter as $key => $value) {
                    if ($value['LOGIC']) {
                        foreach ($value as $subKey => $subValue) {
                            foreach ($subValue as $logicKey => $logicValue) {
                                /** @var string|null $necessaryProperty - Ключ нужного свойства для для фильтра */
                                $necessaryProperty = array_search(substr($logicKey, 2), $filterProperties);
                                if ($necessaryProperty) {
                                    if ($necessaryProperty == 'price') {
                                        $logicValue = (float)StringHelper::cutAllSymbolsFromNumber(
                                            $subKey == '0' ? e($price['from']) : e($price['to'])
                                        );
                                    }
                                    $filteredCatalogUri .= '&' . $necessaryProperty
                                        . '[' . ($subKey == '0' ? 'from' : 'to') . ']='
                                        . $logicValue;
                                }
                            }
                        }
                    } else {
                        $i = 0;
                        /** @var string|null $necessaryProperty - Ключ нужного свойства для для фильтра */
                        $necessaryProperty = array_search($key, $filterProperties);
                        foreach ($value as $subKey => $subValue) {
                            $filteredCatalogUri .= '&' . $necessaryProperty . '[' . $i . ']=' . $subValue;
                            $i++;
                        }
                    }
                }
            }

            if ($currentProductId) {
                $filteredCatalogUri .= '&except_product_id=' . $currentProductId;
            }

            $filteredCatalogUri .= '#js-products-list-anchor';

            return $this->respondWithSuccess(['count' => $diamonds->count(), 'catalog' => $filteredCatalogUri]);
        } catch (Exception $exception) {
            $this->writeErrorLog(self::class, $exception->getMessage());
            return $this->respondWithError();
        }
    }
}

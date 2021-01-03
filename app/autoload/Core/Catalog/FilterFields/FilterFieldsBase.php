<?php

namespace App\Core\Catalog\FilterFields;

use App\Core\Jewelry\Enum\FilterUrlEnum;
use App\Helpers\PriceHelper;
use App\Helpers\UserHelper;
use App\Models\Jewelry\Dicts\FillFilterDataInterface;
use App\Models\Jewelry\Dicts\JewelrySex;

/**
 * Класс, описывающий свойства товаров для фильтрации
 * Class FilterFieldsBase
 *
 * @package App\Core\Catalog\FilterFields
 */
class FilterFieldsBase
{
    const REQUEST_KEY   = 'FILTER_URL';
    const IS_FILTER_KEY = FilterUrlEnum::FILTER_KEY;

    /** @var array|mixed[] $filter Массив, описывающий фильтр */
    private $filter;

    /** @var bool $checkActive Флаг необходимости проверки элемента на активность */
    public static $checkActive        = true;

    public static $checkShapesMapping = false;

    /**
     * @return array
     */
    public function getBaseFilter(): array
    {
        $filter = [];
        if (static::$checkActive) {
            $filter['ACTIVE'] = 'Y';
            $filter['=PROPERTY_SELLING_AVAILABLE_VALUE'] = 'Y';
        }

        return $filter;
    }

    /**
     * Возвращает фильтр для бриллиантов из запроса
     *
     * @return array|mixed[]
     */
    public function getFilter(): array
    {
        $props = $this->getFilterProperties();

        $filter = [];

        $request = context()->getRequest()->toArray();

        //Фильтр используется и в бриллиантах.
        if (self::IS_FILTER_KEY === $request[self::IS_FILTER_KEY]) {
            $request = array_merge(
                $request,
                $this->getTransformUrtToQuery()
            );
        }

        foreach ($request as $name => $value) {
            $key = $props[$name];

            //Преобразовывает данные из алиаса в значения для фильтра
            /** @var FillFilterDataInterface $obj */
            if ($obj = FilterUrlEnum::FILTER_URL_ENUM[$name]) {
                $value = ($obj)::getTransformFilterFromUrl($value);
            }

            if (!$key) {
                continue;
            }

            $from = is_array($value) && isset($value['from']) ? $value['from'] : null;
            $to   = is_array($value) && isset($value['to']) ? $value['to'] : 10000000;
            if ($from !== null && $to !== null) {
                if (in_array(
                    $name,
                    [
                        FilterUrlEnum::PRICE,
                        FilterUrlEnum::PRICE_CARAT,
                    ]
                )) {
                    $this->formatPrice($from, $to);
                }

                $filter[">={$key}"] = $from;
                $filter["<={$key}"] = $to;
                continue;
            }

            if (FilterUrlEnum::GENDERS === $name) {
                $value[] = JewelrySex::TYPE_UNISEX;
            }

            //тот случай, когда разные типы фильтров привязаны к одной сущности
            if (!empty($filter[$key]) && is_array($value)) {

                // ALRSUP-959 - оставляем только общие id
                $filter[$key] = array_intersect($filter[$key], $value);

                // ALRSUP-959 - если общих id не нашлось, добавляем в пустой массив несуществующее значение
                // для того, чтобы получить страницу "Нет подходящих украшений"
                if (empty($filter[$key])) {
                    $filter[$key][] = -1;
                }

                continue;
            }

            $filter[$key] = $value;
        }

        //проверим наличие фильтра, если это статичский фильтр
        if (self::IS_FILTER_KEY === $request[self::IS_FILTER_KEY] && empty($filter)) {
            show404();
        }

        $filter = array_merge($filter, $this->getBaseFilter());

        $this->filter = $filter;

        return $filter;
    }

    /**
     * Преобразует url в фильтр
     *
     * @param string $url
     *
     * @return array
     */
    public function getTransformUrtToQuery(): array
    {
        $request = context()->getRequest()->toArray();

        //Фильтр используется и в бриллиантах.
        if (self::IS_FILTER_KEY !== $request[self::IS_FILTER_KEY]) {
            return $request;
        }

        if (!$request[self::REQUEST_KEY]) {
            return [];
        }

        $result   = [];
        $arParams = explode('/', trim($request[self::REQUEST_KEY], '/'));

        foreach ($arParams as $param) {
            [$key, $paramFilter] = explode(FilterUrlEnum::FILTER_TYPE_DELIMETR, $param);
            $parseFilter = explode(FilterUrlEnum::FILTER_PARAMS_DELIMETR, $paramFilter);
            foreach ($parseFilter as $value) {
                if (in_array($key, FilterUrlEnum::FILTER_RANGE_URL_ENUM)) {
                    $result[$key] = RangeFilter::getTransformFilterFromUrl($value);
                    continue;
                }

                $result[$key][] = $value;
            }
        }

        $this->checkFilterSortRule($result);

        return $result;
    }

    /**
     * Проверим порядок фильров иначе 404
     *
     * @param array $filter
     */
    protected function checkFilterSortRule(array $filter): void
    {
        $sort = null;

        foreach ($filter as $filterName => $params) {
            $currentSort = FilterUrlEnum::FILTER_PRIORITY_NUM[$filterName];

            if (null === $sort) {
                $sort = $currentSort;
                continue;
            }

            if ($sort > $currentSort) {
                show404();
            }

            $sort = $currentSort;
        }
    }

    /**
     * Получаем свойства фильтра
     *
     * @return array
     */
    public function getFilterProperties(): array
    {
        return static::$properties;
    }

    /**
     * Возвращает флаг, указывающий на то, что был применен фильтр.
     *
     * @return bool
     */
    public function hasAppliedFilter(): bool
    {
        $applied = false;
        foreach ($this->filter as $key => $value) {
            if (in_array(str_replace(['<=', '=', '=>'], '', $key), $this->getFilterProperties())) {
                $applied = true;
            }
        }

        return $applied;
    }

    /**
     * Форматирует диапазон цен с учетом типа каталога
     *
     * @param float $priceFrom Цена с
     * @param float $priceTo   Цена по
     *
     * @return void
     */
    public function formatPrice(float &$priceFrom, float &$priceTo): void
    {
        $priceFrom = PriceHelper::getPriceInDefaultCurrency($priceFrom - 1);
        $priceTo   = PriceHelper::getPriceInDefaultCurrency($priceTo + 1);

        if (!UserHelper::isLegalEntity()) {
            $priceFrom = $priceFrom / (1 + 20 / 100);
            $priceTo   = $priceTo / (1 + 20 / 100);
        }
    }

    /**
     * Маппит свойства (например формы по gia с формами по ТУ)
     *
     * @param array|mixed[] $values
     * @param array|mixed[] $mapping
     *
     * @return void
     */
    public static function map(array &$values, array $mapping): void
    {
        foreach ($values as $key => $value) {
            $values[$key] = $mapping[$value];
        }

        $values = call_user_func_array('array_merge', $values);
    }
}

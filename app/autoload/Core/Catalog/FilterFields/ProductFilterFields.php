<?php

namespace App\Core\Catalog\FilterFields;

use App\Helpers\PriceHelper;

/**
 * Класс, описывающий свойства бриллиантов для фильтраци
 * Class DiamondsFilterFields
 *
 * @package App\Core\Catalog\FilterFields
 */
class ProductFilterFields extends FilterFieldsBase
{
    /** @var array|string[] $properties - Массив свойств фильтра */
    public static $properties = [

    ];

    /**
     * Возвращает фильтр для бриллиантов в каталоге
     *
     * @return array|mixed[]
     */
    public static function getFilterForCatalog(): array
    {
        $filter = (new self())->getFilter();

        return $filter;
    }

    /**
     * Возвращает фильтр для бриллиантов в аукционе
     *
     * @return array|mixed[]
     */
    public static function getFilterForAuctionDiamonds(): array
    {
        return (new self())->getFilter();
    }
}

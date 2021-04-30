<?php

namespace App\Core\Product\FilterField;

/**
 * Class PriceFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class PriceFilter extends AbstractNumberPriceFilter
{
    public const PROPERTY_CODE = 'PRICE';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'price';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

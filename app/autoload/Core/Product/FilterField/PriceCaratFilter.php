<?php

namespace App\Core\Product\FilterField;

/**
 * Class PriceCaratFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class PriceCaratFilter extends AbstractNumberPriceFilter
{
    public const PROPERTY_CODE = 'PRICE_CARAT';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'price_carat';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

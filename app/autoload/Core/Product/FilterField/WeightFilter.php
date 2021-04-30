<?php

namespace App\Core\Product\FilterField;

/**
 * Class WeightFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class WeightFilter extends AbstractRangeFilter
{
    public const PROPERTY_CODE = 'WEIGHT';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'weight';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

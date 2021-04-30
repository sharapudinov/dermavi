<?php

namespace App\Core\Product\FilterField;

/**
 * Class DepthFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class DepthFilter extends AbstractRangeFilter
{
    public const PROPERTY_CODE = 'DEPTH';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'depth';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

<?php

namespace App\Core\Product\FilterField;

/**
 * Class CuletFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class CuletFilter extends AbstractPlainFilter
{
    public const PROPERTY_CODE = 'CULET';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'culet';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

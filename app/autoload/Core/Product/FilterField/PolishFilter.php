<?php

namespace App\Core\Product\FilterField;

/**
 * Class PolishFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class PolishFilter extends AbstractPlainFilter
{
    public const PROPERTY_CODE = 'POLISH';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'polish';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

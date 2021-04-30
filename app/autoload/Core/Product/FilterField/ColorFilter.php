<?php

namespace App\Core\Product\FilterField;

/**
 * Class ColorFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class ColorFilter extends AbstractPlainFilter
{
    public const PROPERTY_CODE = 'COLOR';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'color';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

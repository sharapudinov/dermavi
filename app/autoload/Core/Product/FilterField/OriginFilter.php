<?php

namespace App\Core\Product\FilterField;

/**
 * Class OriginFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class OriginFilter extends AbstractPlainFilter
{
    public const PROPERTY_CODE = 'ORIGIN';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'origin';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

<?php

namespace App\Core\Product\FilterField;

/**
 * Class AgeVariantFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class AgeVariantFilter extends AbstractPlainFilter
{
    public const PROPERTY_CODE = 'ORIGIN';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'age_variant';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

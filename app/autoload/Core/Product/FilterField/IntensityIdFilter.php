<?php

namespace App\Core\Product\FilterField;

/**
 * Class IntensityIdFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class IntensityIdFilter extends AbstractPlainFilter
{
    public const PROPERTY_CODE = 'INTENSITY_ID';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'intensity_id';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

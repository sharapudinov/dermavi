<?php

namespace App\Core\Product\FilterField;

/**
 * Class FluorFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class FluorFilter extends AbstractPlainFilter
{
    public const PROPERTY_CODE = 'FLUOR';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'fluorescence';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

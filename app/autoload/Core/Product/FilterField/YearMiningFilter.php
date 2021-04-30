<?php

namespace App\Core\Product\FilterField;

/**
 * Class YearMiningFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class YearMiningFilter extends AbstractPlainFilter
{
    public const PROPERTY_CODE = 'YEAR_MINING';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'year_mining';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

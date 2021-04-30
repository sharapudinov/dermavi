<?php

namespace App\Core\Product\FilterField;

/**
 * Class AgeFilter
 * @todo Изменить тип свойства на "S"
 *
 * @package App\Core\Diamond\FilterField
 */
class AgeFilter extends AbstractRangeFilter
{
    public const PROPERTY_CODE = 'AGE';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'age';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

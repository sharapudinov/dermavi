<?php

namespace App\Core\Product\FilterField;

/**
 * Class TableFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class TableFilter extends AbstractRangeFilter
{
    public const PROPERTY_CODE = 'TABLE';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'table';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

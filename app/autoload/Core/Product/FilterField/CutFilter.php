<?php

namespace App\Core\Product\FilterField;

/**
 * Class CutFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class CutFilter extends AbstractPlainFilter
{
    public const PROPERTY_CODE = 'CUT';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'cut';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

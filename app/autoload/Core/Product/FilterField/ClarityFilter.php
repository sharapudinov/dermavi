<?php

namespace App\Core\Product\FilterField;

/**
 * Class ClarityFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class ClarityFilter extends AbstractPlainFilter
{
    public const PROPERTY_CODE = 'CLARITY';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'clarity';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

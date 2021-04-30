<?php

namespace App\Core\Product\FilterField;

/**
 * Class ShapeFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class ShapeFilter extends AbstractPlainFilter
{
    public const PROPERTY_CODE = 'SHAPE';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'shape';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

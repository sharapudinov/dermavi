<?php

namespace App\Core\Product\FilterField;

/**
 * Class SymmetryFilter
 *
 * @package App\Core\Diamond\FilterField
 */
class SymmetryFilter extends AbstractPlainFilter
{
    public const PROPERTY_CODE = 'SYMMETRY';

    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = 'symmetry';

    /** @var string Имя поля для фильтра */
    protected $filterFieldName = 'PROPERTY_' . self::PROPERTY_CODE;
}

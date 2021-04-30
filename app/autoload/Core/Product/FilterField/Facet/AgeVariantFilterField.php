<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\AgeVariantFilter;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class AgeVariantFilterField
 * Таблица: app_Product_age
 * @package App\Core\Product\FilterField\Facet
 */
class AgeVariantFilterField extends AbstractDirectoryFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = AgeVariantFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'age_variant';

    /** @var string */
    protected $multilingualFieldValue = 'UF_NAME';

    /** @var array */
    protected $directoryItemsSelect = [
        'UF_XML_ID', 'UF_SORT',
        'UF_NAME', 'UF_NAME_EN', 'UF_NAME_RU', 'UF_NAME_CN',
    ];
}

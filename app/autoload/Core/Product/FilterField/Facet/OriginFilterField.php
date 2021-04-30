<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\OriginFilter;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class OriginFilterField
 *
 * @package App\Core\Ptoduct\FilterField\Facet
 */
class OriginFilterField extends AbstractDirectoryFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = OriginFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'origins';

    /** @var string */
    protected $multilingualFieldValue = 'UF_NAME';

    /** @var array */
    protected $directoryItemsSelect = [
        'UF_XML_ID', 'UF_SORT',
        'UF_NAME', 'UF_NAME_EN', 'UF_NAME_RU', 'UF_NAME_CN',
        'UF_LAT', 'UF_LON',
    ];

    /**
     * @internal
     * @param array $item
     * @return array
     */
    public function directoryItemTransformer(array $item): array
    {
        $item = parent::directoryItemTransformer($item);
        if (!$item) {
            return $item;
        }

        // Не выводим регионы без координат
        if (!$item['UF_LAT'] || !$item['UF_LON']) {
            return [];
        }

        return $item;
    }
}

<?php

namespace App\Filtration\Relation;

use App\Filtration\Traits;

/**
 * Class RelationValuesGetter
 *
 * @package App\Filtration\Helper
 */
class RelationValuesGetter
{
    use Traits\RelationDirectoryItemsGetterTrait,
        Traits\RelationIBlockElementsGetterTrait,
        Traits\RelationIBlockSectionsGetterTrait,
        Traits\RelationIBlockEnumPropertyGetterTrait;
}

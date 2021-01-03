<?php

namespace App\Filtration\Factory\FieldDto\BitrixFacet;

use App\Filtration\DataProvider\Embed\BitrixFacetFilterData;
use App\Filtration\Exception\InvalidInstanceException;
use App\Filtration\Factory\FieldDto\AbstractFieldDtoFactory;
use App\Filtration\Relation\RelationValuesGetter;
use App\Filtration\Traits;

/**
 * Class AbstractBitrixFacetFactory
 *
 * @package App\Filtration\Factory\FieldDto\BitrixFacet
 */
abstract class AbstractBitrixFacetFactory extends AbstractFieldDtoFactory
{
    use Traits\FilterFieldDtoBuilder\WithRelationValuesGetterTrait,
        Traits\FilterFieldDtoBuilder\BitrixFacet\WithFacetItemValueFilterTrait;

    /**
     * @return RelationValuesGetter
     */
    protected function buildRelationValueGetter(): RelationValuesGetter
    {
        return new RelationValuesGetter();
    }

    /**
     * @param array $params
     * @return static
     * @throws InvalidInstanceException
     */
    protected function validateParams(array $params)
    {
        parent::validateParams($params);

        if (!($params['facetData'] instanceof BitrixFacetFilterData)) {
            throw new InvalidInstanceException(
                'Facet data must be an instance of ' . BitrixFacetFilterData::class
            );
        }

        return $this;
    }
}

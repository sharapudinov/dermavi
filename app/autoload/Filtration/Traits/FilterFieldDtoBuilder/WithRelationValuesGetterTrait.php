<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder;

use App\Filtration\Relation\RelationValuesGetter;

/**
 * Trait WithRelationValuesGetterTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder
 */
trait WithRelationValuesGetterTrait
{
    /** @var RelationValuesGetter $relationValueGetter */
    protected $relationValueGetter;

    /**
     * @return RelationValuesGetter
     */
    abstract protected function buildRelationValueGetter(): RelationValuesGetter;

    /**
     * @param RelationValuesGetter $relationValueGetter
     * @return static
     */
    public function setRelationValueGetter(RelationValuesGetter $relationValueGetter)
    {
        $this->relationValueGetter = $relationValueGetter;

        return $this;
    }

    /**
     * @return RelationValuesGetter
     */
    public function getRelationValueGetter(): RelationValuesGetter
    {
        if ($this->relationValueGetter === null) {
            $this->relationValueGetter = $this->buildRelationValueGetter();
        }

        return $this->relationValueGetter;
    }
}

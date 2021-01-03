<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder;

use App\Filtration\Factory\FieldDto\AbstractVariantAttributesGenerator;

/**
 * Trait WithVariantAttributesGeneratorTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder
 */
trait WithVariantAttributesGeneratorTrait
{
    /** @var AbstractVariantAttributesGenerator $variantAttributesGenerator */
    protected $variantAttributesGenerator;

    /**
     * @return AbstractVariantAttributesGenerator
     */
    abstract protected function buildVariantAttributesGenerator(): AbstractVariantAttributesGenerator;

    public function getVariantAttributesGenerator(): AbstractVariantAttributesGenerator
    {
        if ($this->variantAttributesGenerator === null) {
            $this->variantAttributesGenerator = $this->buildVariantAttributesGenerator();
        }

        return $this->variantAttributesGenerator;
    }

    /**
     * @param AbstractVariantAttributesGenerator $variantAttributesGenerator
     * @return static
     */
    public function setVariantAttributesGenerator(AbstractVariantAttributesGenerator $variantAttributesGenerator)
    {
        $this->variantAttributesGenerator = $variantAttributesGenerator;

        return $this;
    }
}

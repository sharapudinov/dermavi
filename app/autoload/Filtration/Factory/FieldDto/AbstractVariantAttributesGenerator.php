<?php

namespace App\Filtration\Factory\FieldDto;

use App\Filtration\Dto\FieldDto;

/**
 * Class AbstractVariantAttributesGenerator
 *
 * @package App\Filtration\Factory
 */
abstract class AbstractVariantAttributesGenerator
{
    /**
     * @param array $item
     * @param FieldDto $fieldDto
     * @return bool
     */
    abstract public function generateVariantItemSelected(array $item, FieldDto $fieldDto): bool;

    /**
     * @param array $variantItem
     * @param FieldDto $fieldDto
     * @return string
     */
    abstract public function generateVariantItemUrl(array $variantItem, FieldDto $fieldDto): string;

    /**
     * @param array $variantItem
     * @param FieldDto $fieldDto
     * @return string
     */
    abstract public function generateVariantItemRequestName(array $variantItem, FieldDto $fieldDto): string;

    /**
     * @param array $variantItem
     * @param FieldDto $fieldDto
     * @return string
     */
    abstract public function generateVariantItemHtmlSelector(array $variantItem, FieldDto $fieldDto): string;
}

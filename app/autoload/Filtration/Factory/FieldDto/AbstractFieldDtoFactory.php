<?php

namespace App\Filtration\Factory\FieldDto;

use App\Filtration\Dto\FieldDto;
use App\Filtration\Exception\InvalidInstanceException;
use App\Filtration\Model\FilterFieldInfo;
use App\Filtration\Traits;

/**
 * Class AbstractFieldDtoFactory
 *
 * @package App\Filtration\Factory\FieldDto
 */
abstract class AbstractFieldDtoFactory
{
    use Traits\FilterFieldDtoBuilder\FieldDtoCreatorsTrait,
        Traits\FilterFieldDtoBuilder\FieldDtoFillersTrait,
        Traits\FilterFieldDtoBuilder\WithVariantAttributesGeneratorTrait,
        Traits\FilterFieldDtoBuilder\WithVariantItemTransformerTrait,
        Traits\FilterFieldDtoBuilder\WithFieldDtoFinalizerTrait;

    /**
     * @return VariantAttributesGenerator
     */
    protected function buildVariantAttributesGenerator(): VariantAttributesGenerator
    {
        return new VariantAttributesGenerator();
    }

    /**
     * @param array $params
     * @return static
     * @throws InvalidInstanceException
     */
    protected function validateParams(array $params)
    {
        if (!($params['filterFieldInfo'] instanceof FilterFieldInfo)) {
            throw new InvalidInstanceException(
                'Filter filed info must be an instance of ' . FilterFieldInfo::class
            );
        }

        if (isset($params['appliedFilterRequestValues']) && !is_array($params['appliedFilterRequestValues'])) {
            throw new InvalidInstanceException(
                'Applied filter request values must be an array'
            );
        }

        return $this;
    }

    /**
     * @param array $params
     * @return FieldDto
     * @throws InvalidInstanceException
     */
    public function buildFieldDto(array $params): FieldDto
    {
        $this->validateParams($params);

        $fieldDto = $this->fillFieldDtoVariants(
            $this->fillFieldDto(
                $this->createFieldDto(),
                $params['filterFieldInfo']
            ),
            $this->generateVariantItems($params)
        );

        return $this->finalizeFieldDto($fieldDto);
    }

    /**
     * @param array $params
     * @return array
     */
    abstract protected function generateVariantItems(array $params): array;

    /**
     * @param string $value
     * @param array $appliedFilterRequestValues
     * @return bool
     */
    protected function isValueSelected(string $value, array $appliedFilterRequestValues): bool
    {
        return in_array($value, $appliedFilterRequestValues, false);
    }

    /**
     * @param array $variantItem
     * @param FieldDto $fieldDto
     * @return bool
     */
    protected function generateVariantItemSelected(array $variantItem, FieldDto $fieldDto): bool
    {
        return $this->getVariantAttributesGenerator()->generateVariantItemSelected($variantItem, $fieldDto);
    }

    /**
     * @param array $variantItem
     * @param FieldDto $fieldDto
     * @return string
     */
    protected function generateVariantItemUrl(array $variantItem, FieldDto $fieldDto): string
    {
        return $this->getVariantAttributesGenerator()->generateVariantItemUrl($variantItem, $fieldDto);
    }

    /**
     * @param array $variantItem
     * @param FieldDto $fieldDto
     * @return string
     */
    protected function generateVariantItemRequestName(array $variantItem, FieldDto $fieldDto): string
    {
        return $this->getVariantAttributesGenerator()->generateVariantItemRequestName($variantItem, $fieldDto);
    }

    /**
     * @param array $variantItem
     * @param FieldDto $fieldDto
     * @return string
     */
    protected function generateVariantItemHtmlSelector(array $variantItem, FieldDto $fieldDto): string
    {
        return $this->getVariantAttributesGenerator()->generateVariantItemHtmlSelector($variantItem, $fieldDto);
    }

    /**
     * @param $item
     * @param FieldDto $fieldDto
     * @return array
     */
    protected function transformVariantItemRegular($item, FieldDto $fieldDto): array
    {
        $item['VALUE'] = $item['VALUE'] ?? '';
        $item['NAME'] = (string)($item['NAME'] ?? '');
        $item['SORT'] = (int)($item['SORT'] ?? 0);
        $item['DESCRIPTION'] = (string)($item['FILTER_DESCRIPTION'] ?? '');
        $item['DOC_COUNT'] = (int)($item['DOC_COUNT'] ?? 0);
        $item['HTML_SELECTOR'] = (string)($item['HTML_SELECTOR'] ?? '');

        $item['SELECTED'] = $this->generateVariantItemSelected($item, $fieldDto);
        $item['URL'] = $this->generateVariantItemUrl($item, $fieldDto);
        $item['REQUEST_NAME'] = $this->generateVariantItemRequestName($item, $fieldDto);
        $item['HTML_SELECTOR'] = $this->generateVariantItemHtmlSelector($item, $fieldDto);

        return $item;
    }

    /**
     * @param array $item
     * @param FieldDto $fieldDto
     * @return array
     */
    protected function transformVariantItemInner($item, FieldDto $fieldDto): array
    {
        $item = $this->transformVariantItem(
            $this->transformVariantItemRegular($item, $fieldDto),
            $fieldDto
        );

        return $item;
    }
}

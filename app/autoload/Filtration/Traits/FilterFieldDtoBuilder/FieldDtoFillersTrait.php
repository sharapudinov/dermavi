<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder;

use App\Filtration\Dto\FieldDto;
use App\Filtration\Dto\FieldVariantDto;
use App\Filtration\Model\FilterFieldInfo;

/**
 * Trait FieldDtoFillersTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder
 */
trait FieldDtoFillersTrait
{
    /**
     * @param array $item
     * @param FieldDto $fieldDto
     * @return array
     */
    abstract protected function transformVariantItemInner($item, FieldDto $fieldDto): array;

    /**
     * @return FieldVariantDto
     */
    abstract protected function createFieldVariantDto(): FieldVariantDto;

    /**
     * @param FieldDto $fieldDto
     * @param FilterFieldInfo $filterFieldInfo
     * @return FieldDto
     */
    protected function fillFieldDto(FieldDto $fieldDto, FilterFieldInfo $filterFieldInfo): FieldDto
    {
        $fieldDto->setTitle($filterFieldInfo->getName());
        $fieldDto->setCode($filterFieldInfo->getCode());
        $fieldDto->setDescription($filterFieldInfo->getFilterHint());
        $fieldDto->setDisplayExpanded($filterFieldInfo->isDisplayExpanded());
        $fieldDto->setVisible($filterFieldInfo->isVisible());
        $fieldDto->setDisplayType($filterFieldInfo->getDisplayTypeProcessed());

        return $fieldDto;
    }

    /**
     * @param FieldVariantDto $variantDto
     * @param array $item
     * @return FieldVariantDto
     */
    protected function fillFieldVariantDto(FieldVariantDto $variantDto, array $item): FieldVariantDto
    {
        $variantDto->setValue((string)($item['VALUE'] ?? ''))
            ->setName((string)($item['NAME'] ?? ''))
            ->setDescription((string)($item['DESCRIPTION'] ?? ''))
            ->setSort((int)($item['SORT'] ?? 0))
            ->setSelected((bool)($item['SELECTED'] ?? false))
            ->setRequestName((string)($item['REQUEST_NAME'] ?? ''))
            ->setUrl((string)($item['URL'] ?? ''))
            ->setHtmlSelector((string)($item['HTML_SELECTOR'] ?? ''));

        if (isset($item['RANGE_CODE'])) {
            $variantDto->setRangeCode((string)$item['RANGE_CODE']);
        }

        if (isset($item['DOC_COUNT'])) {
            $variantDto->setDocCount((int)$item['DOC_COUNT']);
        }
        $variantDto->setEnabled($variantDto->getDocCount() > 0);

        if (isset($item['EXTRA'])) {
            $variantDto->setExtra((array)$item['EXTRA']);
        }

        return $variantDto;
    }

    /**
     * @param FieldDto $fieldDto
     * @param array $variantItemsList
     * @return FieldDto
     */
    protected function fillFieldDtoVariants(FieldDto $fieldDto, array $variantItemsList): FieldDto
    {
        foreach ($variantItemsList as $item) {
            $item = $this->transformVariantItemInner($item, $fieldDto);
            if (!$item) {
                continue;
            }
            $fieldDto->getVariants()->push(
                $this->fillFieldVariantDto(
                    $this->createFieldVariantDto(),
                    $item
                )
            );
        }

        return $fieldDto;
    }
}

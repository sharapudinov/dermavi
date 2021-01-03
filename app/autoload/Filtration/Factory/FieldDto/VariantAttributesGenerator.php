<?php

namespace App\Filtration\Factory\FieldDto;

use App\Filtration\Dto\FieldDto;
use App\Filtration\Dto\FieldVariantDto;
use App\Filtration\Enum\DisplayTypeEnum;

/**
 * Class VariantAttributesGenerator
 * Генератор значений связующих полей FieldVariantDto
 *
 * @package App\Filtration\Factory
 */
class VariantAttributesGenerator extends AbstractVariantAttributesGenerator
{
    /**
     * @param array $item
     * @param FieldDto $fieldDto
     * @return bool
     */
    public function generateVariantItemSelected(array $item, FieldDto $fieldDto): bool
    {
        return (bool)($item['SELECTED'] ?? false);
    }

    /**
     * Генерация ссылки для установки/снятия варианта значения фильтра
     *
     * @param array $variantItem
     * @param FieldDto $fieldDto
     * @return string
     */
    public function generateVariantItemUrl(array $variantItem, FieldDto $fieldDto): string
    {
        $url = (string)($variantItem['URL'] ?? '');
        /** @todo сделать генерацию ссылки */
        //if ($url === '') {
        //}

        return $url;
    }

    /**
     * Генерация имени html-поля для варианта значения фильтра
     *
     * @param array $variantItem
     * @param FieldDto $fieldDto
     * @return string
     */
    public function generateVariantItemRequestName(array $variantItem, FieldDto $fieldDto): string
    {
        $requestName = (string)($variantItem['REQUEST_NAME'] ?? '');
        if ($requestName === '') {
            if (($variantItem['RANGE_CODE'] ?? '') !== '' && $fieldDto->getDisplayType() === DisplayTypeEnum::RANGE) {
                $code = $variantItem['RANGE_CODE'];
                if ($variantItem['RANGE_CODE'] === FieldVariantDto::RANGE_CODE_MIN) {
                    $code = FieldVariantDto::RANGE_CODE_FROM;
                } elseif ($variantItem['RANGE_CODE'] === FieldVariantDto::RANGE_CODE_MAX) {
                    $code = FieldVariantDto::RANGE_CODE_TO;
                }
                $variantPart = '[' . $code . ']';
            } elseif ($fieldDto->getDisplayType() === DisplayTypeEnum::RADIOBUTTON) {
                $variantPart = '';
            } else {
                $variantPart = '[]';
            }
            $requestName = sprintf(
                '%s%s',
                $fieldDto->getCode(),
                $variantPart
            );
        }

        return $requestName;
    }

    /**
     * Генерация html-селектора для варианта значения фильтра
     *
     * @param array $variantItem
     * @param FieldDto $fieldDto
     * @return string
     */
    public function generateVariantItemHtmlSelector(array $variantItem, FieldDto $fieldDto): string
    {
        $htmlSelector = (string)($variantItem['HTML_SELECTOR'] ?? '');
        if ($htmlSelector === '') {
            if (($variantItem['RANGE_CODE'] ?? '') !== '' && $fieldDto->getDisplayType() === DisplayTypeEnum::RANGE) {
                $variantPart = (string)$variantItem['RANGE_CODE'];
            } else {
                $variantPart = $this->normalizeHtmlValue((string)$variantItem['VALUE']);
            }

            $htmlSelector = sprintf(
                '%s_%s_%s',
                $fieldDto->getDisplayType(),
                $fieldDto->getCode(),
                $variantPart
            );
        }

        return $htmlSelector;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function normalizeHtmlValue(string $value): string
    {
        return md5(trim($value));
    }
}

<?php

namespace App\Core\Product;

use App\Filtration\Dto\FieldDto;
use App\Helpers\LanguageHelper;
use App\Models\Catalog\Catalog;

/**
 * Class DiamondsFilterMetaTagGenerator
 * Муть, которая была вынесена из компонента фильтра, используемая для генерации заголовка по запросу фильтра.
 * Адаптирована под новую реализацию фильтра.
 *
 * @package App\Core\Diamond\Service
 */
final class ProductFilterMetaTagGenerator
{
    /** @var string */
    private $languageVersion;

    /** @var int */
    private $sectionId;

    /** @var array Массив кодов фильтров, значения которых участвуют в генерации метатегов (соответствуют FieldDto::code) */
    private $filterCodes = [
        'shape',
        'color',
        'fluorescence',
        'year_mining',
        'cut',
        'origin',
    ];

    /**
     * @param int $sectionId
     * @param string|null $languageVersion
     */
    public function __construct(int $sectionId, string $languageVersion = null)
    {
        $this->sectionId = $sectionId;
        $this->languageVersion = $languageVersion ?: LanguageHelper::getLanguageVersion();
    }

    /**
     * @return string
     */
    public function getLanguageVersion(): string
    {
        return $this->languageVersion;
    }

    /**
     * @return int
     */
    public function getSectionId(): int
    {
        return $this->sectionId;
    }

    /**
     * @param iterable|FieldDto[] $filtrationData
     * @param int $pageNum
     * @return array
     */
    public function getMetaTags(iterable $filtrationData, int $pageNum = 1): array
    {
        $selected = $this->getSelectedData($filtrationData);
        $displayValues = $selected ? array_merge(...array_values($selected)) : [];

        $sectionValues = Catalog::getSectionSEOValues($this->getSectionId());

        // Создадим поля с тильда-ключами
        foreach ($sectionValues as $key => $value) {
            $sectionValues['~' . $key] = $value;
        }

        $titleTemplate = (string)($sectionValues['SECTION_PAGE_TITLE'] ?? '');
        $strValues = implode(', ', $displayValues);
        $sectionTitle = str_replace(
            ['#props#', '#page#', '#shape#'],
            [$strValues, $pageNum],
            $titleTemplate
        );

        $sectionValues['SECTION_PAGE_TITLE'] = LanguageHelper::getMarkedTextByLang(
            $sectionTitle,
            $this->getLanguageVersion()
        );

        return $sectionValues;
    }

    /**
     * Возвращает массив значений примененных фильтров для дальнейшей генерации метатегов
     *
     * @param iterable|FieldDto[] $filtrationData
     * @return array
     */
    private function getSelectedData(iterable $filtrationData): array
    {
        $selectedData = [];
        foreach ($filtrationData as $fieldDto) {
            if (!($fieldDto instanceof FieldDto)) {
                continue;
            }

            $code = $fieldDto->getCode();
            if (!in_array($code, $this->filterCodes, true)) {
                continue;
            }

            foreach ($fieldDto->getVariants() as $variantItem) {
                if ($variantItem->isSelected()) {
                    $selectedData[$code][] = $variantItem->getName();
                }
            }
        }

        return $selectedData;
    }
}

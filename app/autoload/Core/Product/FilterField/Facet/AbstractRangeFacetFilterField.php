<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\AbstractPlainFilter;
use App\Core\Product\FilterField\AbstractRangeFilter;
use App\Core\User\Context\UserContext;
use App\Core\User\Interfaces\UserContextAwareInterface;
use App\Filtration\Dto\FieldDto;
use App\Filtration\FilterFieldDtoBuilder\BitrixFacet\AbstractRangeBitrixFacet;
use App\Filtration\Helper\PropertiesHelper;
use App\Filtration\Interfaces\FilterFieldInterface;
use App\Filtration\Model\FilterFieldInfo;
use App\Helpers\IBlock\PropHelper;
use App\Models\Catalog\Catalog;

/**
 * Class AbstractRangeFacetFilterField
 * Поля числовых типов ("диапазон значений от-до")
 *
 * @method AbstractPlainFilter|AbstractRangeFilter getFilterField
 * @package App\Core\Product\FilterField\Facet
 */
abstract class AbstractRangeFacetFilterField extends AbstractRangeBitrixFacet
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance;

    /**
     * @param UserContext|null $userContext
     */
    public function __construct(UserContext $userContext = null)
    {
        parent::__construct();

        if ($userContext !== null) {
            $filterField = $this->getFilterField();
            if ($filterField instanceof UserContextAwareInterface) {
                $filterField->setUserContext($userContext);
            }
        }

        $this->getFieldDtoFactory()->setFieldDtoFinalizer(
            [$this, 'fieldDtoFinalizer']
        );
    }

    /**
     * @return FilterFieldInterface
     */
    protected function buildFilterField(): FilterFieldInterface
    {
        return new $this->filterFieldInstance;
    }

    /**
     * @return int
     */
    public function getIBlockId(): int
    {
        return Catalog::iblockID();
    }

    /**
     * @return int
     */
    public function getFilterFieldEntityPrimaryKey(): int
    {
        return PropHelper::getPropertyId($this->getIBlockId(), $this->getFilterField()::PROPERTY_CODE);
    }

    /**
     * @return FilterFieldInfo
     */
    protected function buildFilterFieldInfo(): FilterFieldInfo
    {
        $filterFieldInfo = FilterFieldInfo::createFromArray(
            PropertiesHelper::getIBlockSectionSmartProperty(
                $this->getFilterFieldEntityPrimaryKey(),
                $this->getIBlockId(),
                $this->getSmartPropertySectionId()
            )
        );
        $filterFieldInfo->setCode(
            $this->getFilterField()->getRequestFieldName()
        );

        return $filterFieldInfo;
    }

    /**
     * @param FieldDto $fieldDto
     * @return FieldDto
     * @internal
     */
    public function fieldDtoFinalizer(FieldDto $fieldDto): FieldDto
    {
        /**
         * Приводим список вариантов range-поля к ожидаемому фронтендом виду:
         * min - минимально возможное значение с учетом наложенных фильтров;
         * max - максимально возможное значение с учетом наложенных фильтров;
         * from - левая граница, заданная юзером;
         * to - правая граница, заданная юзером;
         */
        $minVariant = null;
        $maxVariant = null;
        $selectedFrom = null;
        $selectedTo = null;
        $filteredMin = null;
        $filteredMax = null;
        foreach ($fieldDto->getVariants() as $key => $variant) {
            $rangeCode = $variant->getRangeCode();
            // Оставляем в коллекции только: min, max, from, to.
            $forget = false;
            switch ($rangeCode) {
                case $variant::RANGE_CODE_MIN:
                    // Минимально возможное значение диапазона
                    $minVariant = $variant;
                    break;

                case $variant::RANGE_CODE_FROM:
                    // Выбранное значение "от"
                    $selectedFrom = $variant;
                    break;

                case $variant::RANGE_CODE_MIN_FILTERED:
                    // Уточненное минимально возможное значение диапазона после наложения фильтров
                    $filteredMin = $variant;
                    $forget = true;
                    break;

                case $variant::RANGE_CODE_MAX:
                    // Максимальное возможное значение диапазона
                    $maxVariant = $variant;
                    break;

                case $variant::RANGE_CODE_TO:
                    $selectedTo = $variant;
                    break;

                case $variant::RANGE_CODE_MAX_FILTERED:
                    // Уточненное максимально возможное значение диапазона после наложения фильтров
                    $filteredMax = $variant;
                    $forget = true;
                    break;

                default:
                    $forget = true;
                    break;
            }

            if ($forget) {
                $fieldDto->getVariants()->forget($key);
            }
        }

        /**
         * Варианты min_filtered и max_filtered версткой не поддерживаются.
         * Корректируем границы вариантов min и max так, чтобы в них были значения по уточненному результату,
         * либо заданному фильтру.
         */
        if ($minVariant && $filteredMin) {
            $minVariant
                ->setExtraValue('originalDocCount', $minVariant->getDocCount())
                ->setExtraValue('originalValue', $minVariant->getValue())
                ->setDocCount($filteredMin->getDocCount());

            if ($selectedFrom && (float)$selectedFrom->getValue() < (float)$filteredMin->getValue()) {
                $minVariant
                    ->setValue($selectedFrom->getValue())
                    ->setName($selectedFrom->getName());
            } else {
                $minVariant
                    ->setValue($filteredMin->getValue())
                    ->setName($filteredMin->getName());
            }

            // Идентичные значения удаляем из extra
            if ($minVariant->getExtraValue('originalDocCount') === $minVariant->getDocCount()) {
                $minVariant->unsetExtraValue('originalDocCount');
            }
            if ($minVariant->getExtraValue('originalValue') === $minVariant->getValue()) {
                $minVariant->unsetExtraValue('originalValue');
            }
        }
        if ($maxVariant && $filteredMax) {
            $maxVariant
                ->setExtraValue('originalDocCount', $maxVariant->getDocCount())
                ->setExtraValue('originalValue', $maxVariant->getValue())
                ->setDocCount($filteredMax->getDocCount());

            if ($selectedTo && (float)$selectedTo->getValue() > (float)$filteredMax->getValue()) {
                $maxVariant
                    ->setValue($selectedTo->getValue())
                    ->setName($selectedTo->getName());
            } else {
                $maxVariant
                    ->setValue($filteredMax->getValue())
                    ->setName($filteredMax->getName());
            }

            // Идентичные значения удаляем из extra
            if ($maxVariant->getExtraValue('originalDocCount') === $maxVariant->getDocCount()) {
                $maxVariant->unsetExtraValue('originalDocCount');
            }
            if ($maxVariant->getExtraValue('originalValue') === $maxVariant->getValue()) {
                $maxVariant->unsetExtraValue('originalValue');
            }
        }

        // Пересчет ключей, чтобы при конвертации в json получался массив, а не объект.
        $fieldDto->setVariants($fieldDto->getVariants()->values());

        return $fieldDto;
    }

    /**
     * Округление значений
     *
     * @param FieldDto $fieldDto
     * @param callable $roundMethod
     */
    protected function fieldDtoRoundValues(FieldDto $fieldDto, callable $roundMethod): void
    {
        foreach ($fieldDto->getVariants() as $variantDto) {
            $prevValue = $variantDto->getValue();
            $newValue = $roundMethod($prevValue);
            /** @noinspection TypeUnsafeComparisonInspection */
            if ($newValue != $prevValue) {
                $variantDto->setValue($newValue)
                    ->setName($newValue);
                if ($variantDto->getExtraValue('originalValue') === null) {
                    $variantDto->setExtraValue('originalValue', $prevValue);
                }
            }
        }
    }
}

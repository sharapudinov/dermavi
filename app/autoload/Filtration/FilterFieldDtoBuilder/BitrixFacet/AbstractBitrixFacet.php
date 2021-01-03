<?php

namespace App\Filtration\FilterFieldDtoBuilder\BitrixFacet;

use App\Filtration\DataProvider\Embed\BitrixFacetFilterData;
use App\Filtration\Dto\FieldDto;
use App\Filtration\Exception\InvalidInstanceException;
use App\Filtration\Factory\FieldDto\BitrixFacet\AbstractBitrixFacetFactory;
use App\Filtration\FilterFieldDtoBuilder\AbstractFilterFieldDtoBuilder;
use App\Filtration\Interfaces\BitrixFacet\BitrixFacetFilterGeneratorInterface;
use App\Filtration\Interfaces\BitrixFacet\FilterFieldDtoBuilderBitrixFacetInterface;
use App\Filtration\Interfaces\FilterRequestInterface;
use Bitrix\Iblock\PropertyIndex\Facet;
use App\Filtration\Traits;
use Throwable;

/**
 * Class AbstractBitrixFacet
 * Генерация DTO для полей, находящихся в фасетном индексе "Битрикса"
 *
 * @method AbstractBitrixFacetFactory getFieldDtoFactory
 * @package App\Filtration\FilterFieldDtoBuilder\BitrixFacet
 */
abstract class AbstractBitrixFacet extends AbstractFilterFieldDtoBuilder
    implements FilterFieldDtoBuilderBitrixFacetInterface
{
    use Traits\FilterFieldDtoBuilder\BitrixFacet\FacetFilterRequestApplyingTrait,
        Traits\FilterFieldDtoBuilder\BitrixFacet\WithSmartPropertySectionIdTrait;

    public function __construct()
    {
        /** Заглушка */
    }

    /**
     * @return bool
     */
    public function isFilterFieldEntityPropertyType(): bool
    {
        return true;
    }

    /**
     * @param BitrixFacetFilterData $facetData
     * @return array
     */
    protected function getFieldDtoBuilderParams($facetData): array
    {
        return [
            'filterFieldInfo' => $this->getFilterFieldInfo(),
            'appliedFilterRequestValues' => $this->getAppliedFilterRequestValues(),

            'facetData' => $facetData,
            'propertyId' => $this->getFilterFieldEntityPrimaryKey(),
        ];
    }

    /**
     * @param BitrixFacetFilterData $facetData
     * @return FieldDto
     * @throws InvalidInstanceException
     * @throws Throwable
     */
    public function buildFieldDto($facetData): FieldDto
    {
        return $this->getFieldDtoFactory()->buildFieldDto(
            $this->getFieldDtoBuilderParams($facetData)
        );
    }

    /**
     * @param Facet $facet
     * @param FilterRequestInterface $filterRequest
     * @return array
     */
    public function applyBitrixFacetFilterRequest(Facet $facet, FilterRequestInterface $filterRequest): array
    {
        $appliedValues = [];

        $this->applyFacetFilters(
            $facet,
            $this->generateBitrixFacetFilters(
                $appliedValues,
                $facet,
                $this->getFilterFieldEntityPrimaryKey(),
                $filterRequest
            )
        );

        $this->setAppliedFilterRequestValues($appliedValues);

        return $appliedValues;
    }

    /**
     * @param array $appliedValues
     * @param Facet $facet
     * @param int $facetEntityId ID свойства или цены
     * @param FilterRequestInterface $filterRequest
     * @return array
     */
    protected function generateBitrixFacetFilters(
        array &$appliedValues,
        Facet $facet,
        int $facetEntityId,
        FilterRequestInterface $filterRequest
    ): array
    {
        $filterField = $this->getFilterField();
        if ($filterField instanceof BitrixFacetFilterGeneratorInterface) {
            return $filterField->generateBitrixFacetFilters($appliedValues, $facet, $facetEntityId, $filterRequest);
        }

        return $this->generateBitrixFacetFiltersInner(
            $appliedValues,
            $facet,
            $facetEntityId,
            $this->getBitrixFacetFilterValues($filterRequest)
        );
    }

    /**
     * @param FilterRequestInterface $filterRequest
     * @return mixed
     */
    protected function getBitrixFacetFilterValues(FilterRequestInterface $filterRequest)
    {
        return $filterRequest->getValue(
            $this->getFilterField()->getRequestFieldName()
        );
    }

    /**
     * @param array $appliedValues
     * @param Facet $facet
     * @param int $facetEntityId
     * @param $filterValues
     * @return array
     */
    abstract protected function generateBitrixFacetFiltersInner(
        array &$appliedValues,
        Facet $facet,
        int $facetEntityId,
        $filterValues
    ): array;
}

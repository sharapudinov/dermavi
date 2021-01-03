<?php

namespace App\Filtration\DataProvider;

use App\Filtration\DataProvider\Embed\BitrixFacetDictionaryRelations;
use App\Filtration\DataProvider\Embed\BitrixFacetFilterData;
use App\Filtration\DataProvider\Result\FilterDataResult;
use App\Filtration\Enum\DisplayTypeEnum;
use App\Filtration\Exception\ArgumentEmptyException;
use App\Filtration\Exception\LogicException;
use App\Filtration\Helper\PropertiesHelper;
use App\Filtration\Interfaces\FilterDataProviderInterface;
use App\Filtration\Interfaces\BitrixFacet\FilterFieldDtoBuilderBitrixFacetInterface;
use App\Filtration\Interfaces\FilterRequestInterface;
use App\Filtration\Model\BitrixFacetIndexItem;
use Bitrix\Iblock\PropertyIndex\Facet;
use Bitrix\Iblock\PropertyIndex\Storage;
use Bitrix\Main\DB\Result as DbResult;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;
use CTimeZone;
use Generator;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Class BitrixFacetFilterDataProvider
 *
 * @package App\Filtration\DataProvider
 */
class BitrixFacetFilterDataProvider implements FilterDataProviderInterface
{
    /** @var int */
    protected $iblockId;

    /** @var int */
    protected $sectionId;

    /** @var array */
    protected $priceTypes;

    /** @var FilterFieldDtoBuilderBitrixFacetInterface[] */
    private $filterFields = [];

    /** @var array */
    private $baseFilter = [];

    /** @var array[BitrixFacetIndexItem[]] */
    protected $data = [];

    /** @var array */
    protected $propertyList;

    /** @var Facet */
    protected $facet;

    /** @var array */
    private $filterFieldsIndex = [];

    /** @var BitrixFacetDictionaryRelations */
    protected $dictionaryRelations;

    /**
     * @param int $iblockId
     * @param int $sectionId
     * @param array $priceTypes
     * @throws SystemException
     */
    public function __construct(int $iblockId, int $sectionId = 0, array $priceTypes = [])
    {
        $this->loadModules();

        $this->iblockId = $iblockId;
        $this->sectionId = $sectionId > 0 ? $sectionId : 0;
        $this->priceTypes = $priceTypes;
        $this->dictionaryRelations = new BitrixFacetDictionaryRelations();
    }

    /**
     * @param FilterFieldDtoBuilderBitrixFacetInterface[]|Collection $facetFilterFields
     * @return static
     */
    public function setFilterFields($facetFilterFields)
    {
        $this->filterFields = [];
        foreach ($facetFilterFields as $filterField) {
            if ($filterField instanceof FilterFieldDtoBuilderBitrixFacetInterface) {
                $this->filterFields[] = $filterField;
            }
        }

        $this->flushFilterFieldsIndex();

        return $this;
    }

    /**
     * @return static
     */
    protected function flushFilterFieldsIndex()
    {
        $this->filterFieldsIndex = [];

        return $this;
    }

    /**
     * @return FilterFieldDtoBuilderBitrixFacetInterface[]
     */
    public function getFilterFields(): array
    {
        return $this->filterFields;
    }

    /**
     * @param array $baseFilter
     * @return static
     */
    public function setBaseFilter(array $baseFilter = [])
    {
        $this->baseFilter = $baseFilter;

        return $this;
    }

    /**
     * @return array
     */
    public function getBaseFilter(): array
    {
        return $this->baseFilter;
    }

    /**
     * @return static
     * @throws SystemException
     */
    protected function loadModules()
    {
        try {
            if (!Loader::includeModule('iblock')) {
                throw new SystemException('Iblock module not installed');
            }
        } catch (LoaderException $exception) {
            // ignore
        }

        return $this;
    }

    /**
     * @return Facet
     */
    protected function buildFacet(): Facet
    {
        $facet = new Facet($this->iblockId);
        if ($this->sectionId > 0) {
            $facet->setSectionId($this->sectionId);
        }
        if ($this->priceTypes) {
            $facet->setPrices($this->priceTypes);
        }

        return $facet;
    }

    /**
     * @return Facet
     */
    protected function getFacet(): Facet
    {
        if ($this->facet === null) {
            $this->facet = $this->buildFacet();
        }

        return $this->facet;
    }

    /**
     * @return static
     */
    protected function flushFacet()
    {
        $this->facet = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->getFacet()->isValid();
    }

    /**
     * @return array
     */
    protected function getIBlockSectionFilterableProperties(): array
    {
        return PropertiesHelper::getIBlockSectionSmartProperties($this->iblockId, $this->sectionId);
    }

    /**
     * @return array
     */
    protected function getIBlockProperties(): array
    {
        if ($this->propertyList === null) {
            $this->propertyList = [];
            foreach ($this->getIBlockSectionFilterableProperties() as $property) {
                $this->propertyList[$property['ID']] = [
                    'ID' => $property['ID'],
                    'IBLOCK_ID' => $property['IBLOCK_ID'],
                    'CODE' => $property['CODE'],
                    '~NAME' => $property['NAME'],
                    'NAME' => htmlspecialcharsbx($property['NAME']),
                    'PROPERTY_TYPE' => $property['PROPERTY_TYPE'],
                    'USER_TYPE' => $property['USER_TYPE'] ?? '',
                    'USER_TYPE_SETTINGS' => $property['USER_TYPE_SETTINGS'],
                    'DISPLAY_TYPE' => $property['DISPLAY_TYPE'],
                    'DISPLAY_EXPANDED' => $property['DISPLAY_EXPANDED'],
                    'FILTER_HINT' => $property['FILTER_HINT'],
                    'VALUES' => [],
                ];
            }
        }

        return $this->propertyList;
    }

    /**
     * @param int $propId
     * @return array
     */
    protected function getIBlockPropertyById(int $propId): array
    {
        return $this->getIBlockProperties()[$propId] ?? [];
    }

    /**
     * @param int $facetId
     * @return int
     */
    protected function getIBlockPropertyIdByFacetId(int $facetId): int
    {
        return Storage::facetIdToPropertyId($facetId);
    }

    /**
     * @param int $facetId
     * @return array
     */
    protected function getIBlockPropertyByFacetId(int $facetId): array
    {
        if ($propertyId = $this->getIBlockPropertyIdByFacetId($facetId)) {
            return $this->getIBlockPropertyById($propertyId);
        }

        return [];
    }

    /**
     * @param int $propertyId
     * @return FilterFieldDtoBuilderBitrixFacetInterface|null
     */
    protected function getFilterFieldByPropertyId(int $propertyId): ?FilterFieldDtoBuilderBitrixFacetInterface
    {
        if (!isset($this->filterFieldsIndex['properties'])) {
            $this->filterFieldsIndex['properties'] = [];
            foreach ($this->getFilterFields() as $key => $filterField) {
                if ($filterField->isFilterFieldEntityPropertyType()) {
                    $this->filterFieldsIndex['properties'][$filterField->getFilterFieldEntityPrimaryKey()] = $key;
                }
            }
        }

        $key = $this->filterFieldsIndex['properties'][$propertyId] ?? null;
        if ($key !== null) {
            return $this->filterFields[$key] ?? null;
        }

        return null;
    }

    /**
     * @param Facet $facet
     * @return DbResult|false
     */
    protected function getQueryResult(Facet $facet)
    {
        return $facet->query($this->getBaseFilter());
    }

    /**
     * @param Facet $facet
     * @param FilterRequestInterface $filterRequest
     * @return static
     * @throws Throwable
     */
    protected function applyFilterRequest(Facet $facet, FilterRequestInterface $filterRequest)
    {
        foreach ($this->getFilterFields() as $filterField) {
            $filterField->applyBitrixFacetFilterRequest($facet, $filterRequest);
        }

        return $this;
    }

    /**
     * @param FilterRequestInterface|null $filterRequest
     * @return Generator|FilterDataResult[]|null
     * @throws Throwable
     */
    public function getFilterDataResult(FilterRequestInterface $filterRequest = null): ?Generator
    {
        $this->loadData($filterRequest);

        foreach ($this->data as $facetId => $facetItems) {
            yield $this->buildFilterDataResult((int)$facetId, $facetItems);
        }
    }

    /**
     * @param FilterRequestInterface|null $filterRequest
     * @throws Throwable
     */
    protected function loadData(FilterRequestInterface $filterRequest = null): void
    {
        $this->data = [];
        $this->dictionaryRelations->cleanRelatedData();

        $facet = $this->getFacet();
        if (!$facet->isEmptyWhere()) {
            $facet = $this->flushFacet()->getFacet();
        }

        $iterator = $this->getQueryResult($facet);
        if ($iterator) {
            // Получение вариантов для полей фильтра по умолчанию
            $this->obtainData($iterator);
            // Применяем фильтры, пришедшие из запроса
            if ($filterRequest) {
                $this->applyFilterRequest($facet, $filterRequest);
                if (!$facet->isEmptyWhere()) {
                    // Если фильтры были наложены, то сбросим количество элементов у текущих вариантов значений
                    $this->flushDataElementCount();
                    // Выполняем запрос с наложенными фильтрами
                    $iterator = $this->getQueryResult($facet);
                    if ($iterator) {
                        // Обновляем коллекцию вариантов значений с новыми условиями
                        $this->obtainData($iterator);
                    }
                }
            }
            $this->releaseRelations($facet);
        }
    }

    /**
     * @param DbResult $iterator
     */
    protected function obtainData(DbResult $iterator): void
    {
        CTimeZone::Disable();
        while ($item = $iterator->fetch()) {
            $item = BitrixFacetIndexItem::createFromArray($item);
            if (!isset($this->data[$item->getFacetId()]) && !$this->isFacetEntitySupported($item)) {
                continue;
            }
            try {
                $this->storeDataItem(
                    $this->processFacetItem($item)
                );
            } catch (ArgumentEmptyException $exception) {
                // ignore
            }
        }
        CTimeZone::Enable();
    }

    /**
     * @param BitrixFacetIndexItem $item
     */
    protected function storeDataItem(BitrixFacetIndexItem $item): void
    {
        $facetId = $item->getFacetId();

        if (!$this->isRangeTypeFacetId($facetId)) {
            // Не range-поле.
            $this->data[$facetId][$item->getValue()] = $item;
        } else {
            // Это range-поле.
            // Здесь $item->getValue() нулевой, и нам нужно запомнить только результаты исходной и уточненной выборок
            $valueType = !isset($this->data[$facetId][DisplayTypeEnum::RANGE_VALUE_TYPE_INITIAL])
                ? DisplayTypeEnum::RANGE_VALUE_TYPE_INITIAL
                : DisplayTypeEnum::RANGE_VALUE_TYPE_FILTERED;
            $item->setExtraDataValue('VALUE_TYPE', $valueType);
            $this->data[$facetId][$valueType] = $item;
        }
    }

    /**
     * Является ли поле типом range ("от-до")
     *
     * @param BitrixFacetIndexItem $item
     * @return bool
     */
    protected function isRangeTypeFacetEntity(BitrixFacetIndexItem $item): bool
    {
        return $this->isRangeTypeFacetId($item->getFacetId());
    }

    /**
     * Является ли поле типом range ("от-до")
     *
     * @param int $facetId
     * @return bool
     */
    protected function isRangeTypeFacetId(int $facetId): bool
    {
        // Цена (не свойство) или числовое свойство - это поле "от-до"
        $propertyInfo = $this->getIBlockPropertyByFacetId($facetId);

        return !$propertyInfo || $propertyInfo['PROPERTY_TYPE'] === 'N';
    }

    /**
     * @param BitrixFacetIndexItem $item
     * @return bool
     */
    protected function isFacetEntitySupported(BitrixFacetIndexItem $item): bool
    {
        if ($item->isPropertyType()) {
            $propertyId = $item->getPropertyId();
            if (!$this->getFilterFieldByPropertyId($propertyId) || !$this->getIBlockPropertyById($propertyId)) {
                return false;
            }
        } else {
            /** @todo Сделать обработку полей цен. */
            //$priceId = Storage::facetIdToPriceId($facetId);
            return false;
        }

        return true;
    }

    /**
     * @param BitrixFacetIndexItem $item
     * @return BitrixFacetIndexItem
     * @throws ArgumentEmptyException
     */
    protected function processFacetItem(BitrixFacetIndexItem $item): BitrixFacetIndexItem
    {
        return $item->isPropertyType() ? $this->processPropertyTypeItem($item) : $this->processPriceTypeItem($item);
    }

    /**
     * @param BitrixFacetIndexItem $item
     * @return BitrixFacetIndexItem
     * @throws ArgumentEmptyException
     */
    protected function processPropertyTypeItem(BitrixFacetIndexItem $item): BitrixFacetIndexItem
    {
        $propertyInfo = $this->getIBlockPropertyById($item->getPropertyId());
        if (!$propertyInfo) {
            throw new ArgumentEmptyException('Property info is empty');
        }

        $item->setExtraDataValue('PROPERTY_ID', $propertyInfo['ID']);

        // Значения типа "S" хранятся во внутреннем справочнике фасетного индекса,
        // собираем, чтобы потом групповым запросом их все получить
        if ($propertyInfo['PROPERTY_TYPE'] === 'S') {
            $item->setDictionaryType(true);
            $this->pushDictionaryRelation($item->getValue());
        }

        return $item;
    }

    /**
     * @param BitrixFacetIndexItem $item
     * @return BitrixFacetIndexItem
     */
    protected function processPriceTypeItem(BitrixFacetIndexItem $item): BitrixFacetIndexItem
    {
        /** @todo Сделать обработку полей цен. */
        return $item;
    }

    /**
     * Сброс кол-ва элементов у вариантов значений
     *
     * @return static
     */
    protected function flushDataElementCount()
    {
        /** @var BitrixFacetIndexItem[] $valueItems */
        foreach ($this->data as $facetId => $valueItems) {
            if ($this->isRangeTypeFacetId($facetId)) {
                // Это поле "от-до", обнуление не делаем
                continue;
            }

            foreach ($valueItems as $item) {
                $item->setElementCount(0);
                $item->setExtraDataValue('DISABLED', true);
            }
        }

        return $this;
    }

    /**
     * @param array $facetItems
     * @return BitrixFacetFilterData
     */
    protected function buildBitrixFacetFilterData(array $facetItems): BitrixFacetFilterData
    {
        return (new BitrixFacetFilterData($this->getFacet(), $this->dictionaryRelations))
            ->setFacetItems($facetItems);
    }

    /**
     * @param int $facetId
     * @param BitrixFacetIndexItem[] $facetItems
     * @return FilterDataResult
     * @throws LogicException
     * @throws Throwable
     */
    protected function buildFilterDataResult(int $facetId, array $facetItems): FilterDataResult
    {
        if (Storage::isPropertyId($facetId)) {
            $propertyId = $this->getIBlockPropertyIdByFacetId($facetId);
            $filterField = $propertyId ? $this->getFilterFieldByPropertyId($propertyId) : null;
            if (!$filterField) {
                throw new LogicException('Filter field is not supported');
            }

            $fieldDto = $filterField->buildFieldDto(
                $this->buildBitrixFacetFilterData($facetItems)
            );

            return (new FilterDataResult())
                ->setFilterField($filterField)
                ->setFieldDto($fieldDto);
        }

        throw new LogicException('Not implemented yet');
    }

    /**
     * @param int $dictionaryValueId
     * @return static
     */
    protected function pushDictionaryRelation(int $dictionaryValueId)
    {
        $this->dictionaryRelations->pushDictionaryRelation($dictionaryValueId);

        return $this;
    }

    /**
     * @param Facet $facet
     * @return static
     */
    protected function releaseRelations(Facet $facet)
    {
        $this->dictionaryRelations->releaseRelations($facet);

        return $this;
    }
}

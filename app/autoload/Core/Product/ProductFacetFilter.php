<?php

namespace App\Core\Product;

use App\Core\Product\FilterField\Facet as FacetFields;
use App\Core\User\Context\UserContext;
use App\Filtration\Collection\VariantDtoCollection;
use App\Filtration\DataProvider\BitrixFacetFilterDataProvider;
use App\Filtration\DataProvider\Result\FilterDataResult;
use App\Filtration\Dto\FieldVariantDto;
use App\Filtration\Interfaces\BitrixFacet\FilterFieldDtoBuilderBitrixFacetInterface;
use App\Filtration\Interfaces\FilterRequestInterface;
use App\Helpers\PriceHelper;
use App\Helpers\UserHelper;
use App\Models\Catalog\Catalog;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
use Exception;
use Illuminate\Support\Collection;
use Throwable;
use Traversable;

/**
 * Class ProductsFacetFilter
 *
 * @package App\Core\Product\Service
 */
class ProductFacetFilter
{
    /** @var string */
    public const CACHE_DIR = '/Product_facet_filter';

    /** @var BitrixFacetFilterDataProvider */
    protected $dataProvider;

    /** @var int */
    protected $sectionId = 0;

    /** @var array */
    protected $baseFilter = [
        'ACTIVE' => 'Y',
        '=PROPERTY_SELLING_AVAILABLE_VALUE' => 'Y',
    ];

    /** @var Collection */
    protected $filterFields;

    /** @var UserContext */
    protected $userContext;

    /**
     * @param int $sectionId
     * @param array|null $baseFilter
     * @param UserContext|null $userContext
     */
    public function __construct(int $sectionId = 0, array $baseFilter = null, UserContext $userContext = null)
    {
        $this->sectionId = $sectionId;
        if ($baseFilter !== null) {
            $this->baseFilter = $baseFilter;
        }

        if ($userContext !== null) {
            $this->userContext = $userContext;
        }

        try {
            Loader::includeModule('iblock');
            Loader::includeModule('highloadblock');
        } catch (Exception $exception) {
            // ignore
        }
    }

    /**
     * @return int
     */
    protected function getIBlockId(): int
    {
        return Catalog::iblockID();
    }

    /**
     * @return int
     */
    public function getSectionId(): int
    {
        return $this->sectionId;
    }

    /**
     * @return array
     */
    public function getBaseFilter(): array
    {
        return $this->baseFilter;
    }

    /**
     * @return UserContext
     */
    public function getUserContext(): UserContext
    {
        return $this->userContext ?? UserContext::getCurrent();
    }

    /**
     * @return BitrixFacetFilterDataProvider
     * @throws SystemException
     */
    protected function buildDataProvider(): BitrixFacetFilterDataProvider
    {
        $dataProvider = new BitrixFacetFilterDataProvider(
            $this->getIBlockId(),
            $this->getSectionId()
        );
        $dataProvider->setBaseFilter($this->getBaseFilter());
        $dataProvider->setFilterFields($this->getFilterFields());

        return $dataProvider;
    }

    /**
     * @return BitrixFacetFilterDataProvider
     * @throws SystemException
     */
    public function getDataProvider(): BitrixFacetFilterDataProvider
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = $this->buildDataProvider();
        }

        return $this->dataProvider;
    }

    /**
     * @return Collection
     */
    protected function buildFilterFields(): Collection
    {
        // Контекст устанавливаем полям только в том случае, если он отличается от текущего
        $userContext = $this->getUserContext();
        $customUserContext = $userContext->isCurrent() ? null : $userContext;

        $filterFields = new Collection(
            [
                // shapes | Форма
                new FacetFields\ShapeFilterField($customUserContext),
                // carats
                new FacetFields\WeightFilterField($customUserContext),
                // colors | Цвет
                new FacetFields\ColorFilterField($customUserContext),
                // quality_groups
                new FacetFields\CutFilterField($customUserContext),
                // origins
                new FacetFields\OriginFilterField($customUserContext),
                // years_of_mining
                new FacetFields\YearMiningFilterField($customUserContext),
                // prices[max], prices[min] Здесь
                new FacetFields\PriceFilterField($customUserContext),
                // prices[price_per_carat_max], prices[price_per_carat_min]
                new FacetFields\PriceCaratFilterField($customUserContext),
                // fluorescences
                new FacetFields\FluorFilterField($customUserContext),
                // polishes
                new FacetFields\PolishFilterField($customUserContext),
                // symmetries
                new FacetFields\SymmetryFilterField($customUserContext),
                // culets
                new FacetFields\CuletFilterField($customUserContext),
                // ages (не используется, + надо изменить тип свойства на "S" и включить в фасетный индекс)
                //new FacetFields\AgeFilterField($customUserContext),
                // clarity
                new FacetFields\ClarityFilterField($customUserContext),
                // depth
                new FacetFields\DepthFilterField($customUserContext),
                // table
                new FacetFields\TableFilterField($customUserContext),
                // intensity_id (не используется, + надо включить в фасетный индекс)
                //new FacetFields\IntensityIdFilterField($customUserContext),
            ]
        );

        return $filterFields;
    }

    /**
     * @return Collection|FilterFieldDtoBuilderBitrixFacetInterface[]
     */
    public function getFilterFields(): Collection
    {
        if ($this->filterFields === null) {
            $this->filterFields = $this->buildFilterFields();
        }

        return $this->filterFields;
    }

    /**
     * @return string
     */
    protected function getCacheDir(): string
    {
        return static::CACHE_DIR;
    }

    /**
     * @param FilterRequestInterface $filterRequest
     * @return string
     */
    protected function getCacheKey(FilterRequestInterface $filterRequest): string
    {
        $cacheParams = [];
        $filterFields = $this->getFilterFields();
        foreach ($filterFields as $filterField) {
            $fieldName = $filterField->getFilterField()->getRequestFieldName();
            $value = $filterRequest->getValue($fieldName);
            if ($value !== null) {
                $cacheParams[$fieldName] = $value;
            }
        }
        $cacheKey = md5(
            json_encode(
                [
                    $this->getSectionId(),
                    $this->getUserContext()->getLanguageVersion(),
                    $this->getUserContext()->getCurrency(),
                    $this->getUserContext()->isLegalEntity(),
                    $cacheParams,
                ],
                JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR
            )
        );

        return $cacheKey;
    }

    /**
     * @param FilterRequestInterface $filterRequest
     * @return Collection|FilterDataResult[]
     * @throws SystemException
     * @throws Throwable
     */
    protected function getFilterDataResultCollectionInner(FilterRequestInterface $filterRequest): Collection
    {
        $generator = $this->getDataProvider()->getFilterDataResult($filterRequest);
        $collection = new Collection();
        foreach ($generator as $item) {
            $collection->push($item);
        }

        return $collection;
    }

    /**
     * @param FilterRequestInterface $filterRequest
     * @param int $cacheTtl Время кеширования, мин.
     * @return Collection|FilterDataResult[]
     */
    public function getFilterDataResultCollection(FilterRequestInterface $filterRequest, int $cacheTtl = 60): Collection
    {
        $result = cache(
            $this->getCacheKey($filterRequest) . '|' . $cacheTtl,
            $cacheTtl,
            function () use ($filterRequest) {
                cache_manager()->StartTagCache($this->getCacheDir());
                cache_manager()->RegisterTag('iblock_id_' . $this->getIBlockId());

                $cacheData = $this->getFilterDataResultCollectionInner($filterRequest);

                cache_manager()->EndTagCache();

                return $cacheData;
            },
            $this->getCacheDir()
        );

        return $result;
    }

    /**
     * @deprecated
     * Конвертация в старую структуру компонентов фильтров
     * (чтобы не переделывать шаблоны)
     *
     * @param Traversable|FilterDataResult[] $filterDataResult
     * @return array
     */
    public function convertToLegacyStructure(Traversable $filterDataResult): array
    {
        $filterParameters = [
            'shapes' => new Collection(),
            // Вес бриллианта в каратах
            'carats' => [
                'min' => 0,
                'max' => 0,
            ],
            'colors' => [],
            'quality_groups' => new Collection(),
            'origins' => new Collection(),
            'years_of_mining' => new Collection(),
            'prices' => [
                'max' => 0,
                'min' => 0,
                'price_per_carat_max' => 0,
                'price_per_carat_min' => 0,
            ],
            'fluorescences' => new Collection(),
            'polishes' => new Collection(),
            'symmetries' => new Collection(),
            'culets' => new Collection(),
            'ages' => new Collection(),
            'clarity' => [],
            'table' => [
                'min' => 0,
                'max' => 0,
            ],
        ];
        $filterParametersExtra = [];

        foreach ($filterDataResult as $resultItem) {
            $filterField = $resultItem->getFilterField();
            $fieldDto = $resultItem->getFieldDto();
            if (!$fieldDto || !$filterField) {
                continue;
            }

            $code = $filterField->getFilterFieldDtoCode();
            switch ($code) {
                case 'colors':
                case 'clarity':
                    $filterParametersExtra[$code] = $this->getFieldDtoVariantsValues($fieldDto->getVariants());
                    $filterParameters[$code] = array_keys($filterParametersExtra[$code]);
                    break;

                case 'carats':
                case 'table':
                    $minMax = $this->getFieldDtoVariantsMinMax($fieldDto->getVariants());
                    $filterParameters[$code]['min'] = $minMax['min'] ?? $filterParameters[$code]['min'];
                    $filterParameters[$code]['max'] = $minMax['max'] ?? $filterParameters[$code]['max'];
                    break;

                case 'price_per_carat':
                    $minMax = $this->getFieldDtoVariantsMinMax($fieldDto->getVariants());
                    $filterParameters['prices']['price_per_carat_min'] = $this->recalculatePrice(
                        $minMax['min'] ?? $filterParameters['prices']['price_per_carat_min']
                    );
                    $filterParameters['prices']['price_per_carat_max'] = $this->recalculatePrice(
                        $minMax['max'] ?? $filterParameters['prices']['price_per_carat_max']
                    );
                    break;

                case 'prices':
                    $minMax = $this->getFieldDtoVariantsMinMax($fieldDto->getVariants());
                    $filterParameters['prices']['min'] = $this->recalculatePrice(
                        $minMax['min'] ?? $filterParameters['prices']['min']
                    );
                    $filterParameters['prices']['max'] = $this->recalculatePrice(
                        $minMax['max'] ?? $filterParameters['prices']['max']
                    );
                    break;

                case 'years_of_mining':
                    $filterParametersExtra[$code] = $this->getFieldDtoVariantsValues($fieldDto->getVariants());
                    $filterParameters[$code] = new Collection(array_keys($filterParametersExtra[$code]));
                    break;

                case 'shapes':
                    $filterParametersExtra[$code] = $this->getFieldDtoVariantsValues($fieldDto->getVariants());
                    $filterParameters[$code] = $this->getDictionaryCollection(
                        array_keys($filterParametersExtra[$code]),
                        CatalogShape::query()
                    );
                    break;

                case 'quality_groups':
                    $filterParametersExtra[$code] = $this->getFieldDtoVariantsValues($fieldDto->getVariants());
                    $filterParameters[$code] = $this->getDictionaryCollection(
                        array_keys($filterParametersExtra[$code]),
                        Quality::query()
                    );
                    break;

                case 'culets':
                    $filterParametersExtra[$code] = $this->getFieldDtoVariantsValues($fieldDto->getVariants());
                    $filterParameters[$code] = $this->getDictionaryCollection(
                        array_keys($filterParametersExtra[$code]),
                        Culet::query()
                    );
                    break;

                case 'origins':
                    $filterParametersExtra[$code] = $this->getFieldDtoVariantsValues($fieldDto->getVariants());
                    $filterParameters[$code] = $this->getDictionaryCollection(
                        array_keys($filterParametersExtra[$code]),
                        StoneLocation::query()->addFilter(['!=UF_LAT' => null, '!=UF_LON' => null])
                    );
                    break;

                case 'fluorescences':
                    $filterParametersExtra[$code] = $this->getFieldDtoVariantsValues($fieldDto->getVariants());
                    $filterParameters[$code] = $this->getDictionaryCollection(
                        array_keys($filterParametersExtra[$code]),
                        CatalogFluorescence::query()->sort('UF_SORT', 'ASC')
                    );
                    break;

                case 'polishes':
                    $filterParametersExtra[$code] = $this->getFieldDtoVariantsValues($fieldDto->getVariants());
                    $filterParameters[$code] = $this->getDictionaryCollection(
                        array_keys($filterParametersExtra[$code]),
                        Polish::query()->addFilter(['!=UF_NAME'  => 'None'])
                    );
                    break;

                case 'symmetries':
                    $filterParametersExtra[$code] = $this->getFieldDtoVariantsValues($fieldDto->getVariants());
                    $filterParameters[$code] = $this->getDictionaryCollection(
                        array_keys($filterParametersExtra[$code]),
                        Symmetry::query()->addFilter(['!=UF_NAME'  => 'None'])
                    );
                    break;
            }
        }

        return compact(['filterParameters', 'filterParametersExtra']);
    }

    /**
     * @param VariantDtoCollection|FieldVariantDto[] $variantDtoCollection
     * @return array
     */
    private function getFieldDtoVariantsValues($variantDtoCollection): array
    {
        $result = [];
        foreach ($variantDtoCollection as $item) {
            $value = trim($item->getValue());
            if ($value !== '') {
                $result[$value] = (int)$item->getDocCount();
            }
        }

        return $result;
    }

    /**
     * @param VariantDtoCollection|FieldVariantDto[] $variantDtoCollection
     * @return array
     */
    private function getFieldDtoVariantsMinMax($variantDtoCollection): array
    {
        $result = [];
        foreach ($variantDtoCollection as $item) {
            $result[$item->getRangeCode()] = $item->getValue();
        }

        return $result;
    }

    /**
     * Перерасчет цены в текущей валюте
     *
     * @param float $price
     * @param string|null $currency
     * @return float
     */
    private function recalculatePrice(float $price, ?string $currency = null): float
    {
        if (!UserHelper::isLegalEntity()) {
            $price = PriceHelper::calculateWithTax($price);
        }

        return PriceHelper::getPriceInCurrentCurrency($price, $currency);
    }

    /**
     * @param array $xmlIdList
     * @param BaseQuery $queryInstance
     * @return Collection
     */
    private function getDictionaryCollection(array $xmlIdList, BaseQuery $queryInstance): Collection
    {
        if (!$xmlIdList) {
            return new Collection();
        }

        return $queryInstance->addFilter(['=UF_XML_ID' => $xmlIdList])->getList();
    }
}

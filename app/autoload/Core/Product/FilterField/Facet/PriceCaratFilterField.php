<?php
/** @noinspection PhpUnusedParameterInspection */

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\AbstractNumberPriceFilter;
use App\Core\Product\FilterField\PriceCaratFilter;
use App\Core\User\Context\UserContext;
use App\Filtration\Dto\FieldDto;
use App\Filtration\Interfaces\FilterFieldInterface;
use App\Filtration\Interfaces\FilterRequestInterface;
use Bitrix\Main\ObjectNotFoundException;

/**
 * Class PriceCaratFilterField
 *
 * @method PriceCaratFilter getFilterField
 * @package App\Core\Product\FilterField\Facet
 */
class PriceCaratFilterField extends AbstractRangeFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = PriceCaratFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'price_per_carat';

    /**
     * @param UserContext|null $userContext
     */
    public function __construct(UserContext $userContext = null)
    {
        parent::__construct();

        if ($userContext !== null) {
            $this->getFilterField()->setUserContext($userContext);
        }

        $dtoFactory = $this->getFieldDtoFactory();
        $dtoFactory->setVariantItemTransformer(
            [$this, 'variantItemTransformer']
        );
    }

    /**
     * @param array $item
     * @param FieldDto $fieldDto
     * @return array
     * @throws ObjectNotFoundException
     * @internal
     */
    public function variantItemTransformer($item, FieldDto $fieldDto): array
    {
        $filterField = $this->getFilterField();
        if ($filterField instanceof AbstractNumberPriceFilter) {
            // Конвертируем цену для заданного контекста
            $item['VALUE'] = $filterField->recalculatePrice((float)$item['VALUE']);
            $item['NAME'] = $item['VALUE'];
        }

        return $item;
    }

    /**
     * @param FilterRequestInterface $filterRequest
     * @return mixed
     * @throws ObjectNotFoundException
     */
    protected function getBitrixFacetFilterValues(FilterRequestInterface $filterRequest)
    {
        $filterValues = parent::getBitrixFacetFilterValues($filterRequest);

        // Конвертация валюты из запроса в валюту БД + налоги
        $filterField = $this->getFilterField();
        if ($filterField instanceof AbstractNumberPriceFilter) {
            $filterValues = $filterField->formatBitrixFacetFilterValues($filterValues);
        }

        return $filterValues;
    }

    /**
     * @param FieldDto $fieldDto
     * @return FieldDto
     */
    public function fieldDtoFinalizer(FieldDto $fieldDto): FieldDto
    {
        $fieldDto = parent::fieldDtoFinalizer($fieldDto);

        // Округление значений на стадии финализации FieldDto
        $this->fieldDtoRoundValues($fieldDto, 'ceil');

        return $fieldDto;
    }
}

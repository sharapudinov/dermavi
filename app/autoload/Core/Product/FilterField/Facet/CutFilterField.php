<?php
/** @noinspection PhpUnusedParameterInspection */

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\CutFilter;
use App\Core\User\Context\UserContext;
use App\Filtration\Dto\FieldDto;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class CutFilterField
 *
 * @package App\Core\Product\FilterField\Facet
 */
class CutFilterField extends AbstractDirectoryFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = CutFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'quality_groups';

    /** @var array Исключаемые значения справочника по UF_NAME (в нижнем регистре) */
    protected $directoryItemsExcludedNames = [
        'г', 'гр0', 'poor',
    ];

    /**
     * @param UserContext|null $userContext
     */
    public function __construct(UserContext $userContext = null)
    {
        parent::__construct($userContext);

        $dtoFactory = $this->getFieldDtoFactory();
        $dtoFactory->setVariantItemTransformer(
            [$this, 'variantItemTransformer']
        );
    }

    /**
     * @internal
     * @param array $item
     * @param FieldDto $fieldDto
     * @return array
     */
    public function variantItemTransformer($item, FieldDto $fieldDto): array
    {
        // Код для иконки чистоты бриллианта
        $engValue = (string)($item['*']['UF_DISPLAY_VALUE_EN'] ?? '');
        $item['EXTRA']['iconCode'] = (string)mb_strtolower(str_replace(' ', '_', $engValue));

        return $item;
    }
}

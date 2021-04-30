<?php
/** @noinspection PhpUnusedParameterInspection */

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\ShapeFilter;
use App\Core\User\Context\UserContext;
use App\Filtration\Dto\FieldDto;
use App\Filtration\Interfaces\FilterFieldInterface;
use App\Models\Catalog\HL\CatalogShape;

/**
 * Class ShapeFilterField
 *
 * @package App\Core\Product\FilterField\Facet
 */
class ShapeFilterField extends AbstractDirectoryFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = ShapeFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'shapes';

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
        // Символьный код, используемый для вывода иконок форм бриллиантов
        $item['EXTRA']['iconCode'] = CatalogShape::transformShapeIdToIconCode((string)($item['VALUE'] ?? ''));

        return $item;
    }
}

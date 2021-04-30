<?php
/** @noinspection PhpUnusedParameterInspection */

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\ColorFilter;
use App\Core\User\Context\UserContext;
use App\Filtration\Dto\FieldDto;
use App\Filtration\Interfaces\FilterFieldInterface;
use App\Helpers\ColorHelper;

/**
 * Class ColorFilterField
 *
 * @package App\Core\Product\FilterField\Facet
 */
class ColorFilterField extends AbstractDirectoryFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = ColorFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'colors';

    /** @var ColorHelper */
    private $colorHelper;

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
        if ($this->colorHelper === null) {
            $this->colorHelper = ColorHelper::init();
        }

        $value = (string)($item['VALUE'] ?? '');
        // Является ли фантазийным (цветным) камнем
        $item['EXTRA']['isFancy'] = $value !== '' ? $this->colorHelper->hasFancy([$value]) : false;
        // Hex-код цвета
        $item['EXTRA']['fancyColorHex'] = $this->colorHelper->getFancyColorHex($value);
        // Код группы бесцветных (Бесцветные, почти бесцветные, легкий оттенок)
        $item['EXTRA']['colorlessGroup'] = $this->colorHelper->getColorlessGroup($value);
        // Индекс ценности цвета (чем ниже значение, тем ценнее)
        $item['EXTRA']['valueIndexSort'] = $this->colorHelper->getValueIndexSort($value);
        // Относится ли цвет к группе основных цветов (бесцветных и фантазийных)
        $item['EXTRA']['isMainGroupColor'] = $item['EXTRA']['valueIndexSort'] !== null;

        return $item;
    }
}

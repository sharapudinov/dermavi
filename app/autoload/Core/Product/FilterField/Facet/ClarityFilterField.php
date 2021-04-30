<?php
/** @noinspection PhpUnusedParameterInspection */

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\ClarityFilter;
use App\Core\User\Context\UserContext;
use App\Filtration\Dto\FieldDto;
use App\Filtration\Interfaces\FilterFieldInterface;
use App\Helpers\ClarityHelper;

/**
 * Class ClarityFilterField
 *
 * @package App\Core\Product\FilterField\Facet
 */
class ClarityFilterField extends AbstractDirectoryFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = ClarityFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'clarity';

    /** @var array */
    protected $directoryItemsSort = [
        'UF_SORT' => 'ASC',
    ];

    /** @var array Коды, которые допускаются для вывода в фильтрах */
    protected $availableXmlIdList = [
        'if', 'vvs1', 'vvs2', 'vs1', 'vs2', 'si1', 'si2', 'si3', 'i1', 'i2',
    ];

    /** @var ClarityHelper */
    private $clarityHelper;

    /**
     * @param UserContext|null $userContext
     */
    public function __construct(UserContext $userContext = null)
    {
        parent::__construct($userContext);

        $dtoFactory = $this->getFieldDtoFactory();

        $dtoFactory->setFacetItemsValuesFilter(
            [$this, 'facetItemsValuesFilter']
        );

        $dtoFactory->setVariantItemTransformer(
            [$this, 'variantItemTransformer']
        );
    }

    /**
     * @internal
     * @param array $values
     * @return array
     */
    public function facetItemsValuesFilter($values): array
    {
        $values = array_filter(
            $values,
            function ($xmlId) {
                return in_array(mb_strtolower($xmlId), $this->availableXmlIdList, true);
            }
        );

        return $values;
    }

    /**
     * @internal
     * @param array $item
     * @param FieldDto $fieldDto
     * @return array
     */
    public function variantItemTransformer($item, FieldDto $fieldDto): array
    {
        if ($this->clarityHelper === null) {
            $this->clarityHelper = ClarityHelper::init();
        }

        $value = (string)($item['VALUE'] ?? '');
        // Код группы чистоты
        $item['EXTRA']['groupCode'] = (string)$this->clarityHelper->getGroupCode($value);

        return $item;
    }
}

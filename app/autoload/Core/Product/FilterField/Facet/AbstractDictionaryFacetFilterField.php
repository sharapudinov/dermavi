<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\AbstractPlainFilter;
use App\Core\Product\FilterField\AbstractRangeFilter;
use App\Core\User\Context\UserContext;
use App\Core\User\Interfaces\UserContextAwareInterface;
use App\Filtration\FilterFieldDtoBuilder\BitrixFacet\AbstractDictionaryBitrixFacet;
use App\Filtration\Helper\PropertiesHelper;
use App\Filtration\Interfaces\FilterFieldInterface;
use App\Filtration\Model\FilterFieldInfo;
use App\Helpers\IBlock\PropHelper;
use App\Models\Catalog\Catalog;

/**
 * Class AbstractDictionaryFacetFilterField
 * Поля строкового типа
 *
 * @method AbstractPlainFilter|AbstractRangeFilter getFilterField
 * @package App\Core\Product\FilterField\Facet
 */
abstract class AbstractDictionaryFacetFilterField extends AbstractDictionaryBitrixFacet
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
}

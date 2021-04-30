<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\AbstractPlainFilter;
use App\Core\Product\FilterField\AbstractRangeFilter;
use App\Core\User\Context\UserContext;
use App\Core\User\Interfaces\UserContextAwareInterface;
use App\Filtration\FilterFieldDtoBuilder\BitrixFacet\AbstractDirectoryBitrixFacet;
use App\Filtration\Helper\PropertiesHelper;
use App\Filtration\Interfaces\FilterFieldInterface;
use App\Filtration\Model\FilterFieldInfo;
use App\Helpers\IBlock\PropHelper;
use App\Helpers\LanguageHelper;
use App\Models\Catalog\Catalog;

/**
 * Class AbstractDirectoryFacetFilterField
 * Поля типа "Справочник"
 *
 * @method AbstractPlainFilter|AbstractRangeFilter getFilterField
 * @package App\Core\Product\FilterField\Facet
 */
abstract class AbstractDirectoryFacetFilterField extends AbstractDirectoryBitrixFacet
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance;

    /** @var string  */
    protected $multilingualFieldValue = 'UF_DISPLAY_VALUE';

    /** @var array */
    protected $directoryItemsSelect = [
        'UF_XML_ID', 'UF_SORT',
        'UF_NAME', 'UF_DISPLAY_VALUE_EN', 'UF_DISPLAY_VALUE_RU', 'UF_DISPLAY_VALUE_CN',
    ];

    /** @var array */
    protected $directoryItemsSort = [];

    /** @var array Исключаемые значения справочника по UF_NAME (в нижнем регистре) */
    protected $directoryItemsExcludedNames = [];

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

        $dtoFactory = $this->getFieldDtoFactory();

        $dtoFactory->getRelationValueGetter()
            ->setDirectoryItemsSelect($this->directoryItemsSelect)
            ->setDirectoryItemsSort($this->directoryItemsSort)
            // Задаем трансформер результата выборки из таблицы справочника
            ->setDirectoryItemsTransformer(
                [$this, 'directoryItemTransformer']
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
     * @return string|null
     */
    protected function getLanguageSelected(): ?string
    {
        $selectedLang = null;
        $filterField = $this->getFilterField();
        if ($filterField instanceof UserContextAwareInterface) {
            $selectedLang = $filterField->getUserContext()->getLanguageVersion();
        }

        return $selectedLang;
    }

    /**
     * @internal
     * @param array $item
     * @return array
     */
    public function directoryItemTransformer(array $item): array
    {
        // Проверка имени на вхождение в список для исключения из результата
        if ($this->directoryItemsExcludedNames) {
            $checkValue = mb_strtolower(trim($item['UF_NAME'] ?? ''));
            if (in_array($checkValue, $this->directoryItemsExcludedNames, true)) {
                return [];
            }
        }

        if ($this->multilingualFieldValue !== '') {
            $newValue = LanguageHelper::getMultilingualFieldValue(
                $item,
                $this->multilingualFieldValue,
                '',
                $this->getLanguageSelected()
            );
            if ($newValue !== '') {
                // Переопределение выводимого значения (VALUE передается в поле "name" варианта)
                $item['VALUE'] = $newValue;
            }
        }

        return $item;
    }
}

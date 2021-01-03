<?php

namespace App\Filtration\DataProvider\Embed;

use App\Filtration\Enum\DisplayTypeEnum;
use App\Filtration\Model\BitrixFacetIndexItem;
use Bitrix\Iblock\PropertyIndex\Facet;

/**
 * Class BitrixFacetFilterData
 *
 * @package App\Filtration\DataProvider\Embed
 */
class BitrixFacetFilterData
{
    /** @var Facet */
    protected $facet;

    /** @var BitrixFacetIndexItem[] */
    protected $facetItems = [];

    /** @var BitrixFacetDictionaryRelations */
    protected $facetDictionaryRelations;

    /**
     * @param Facet $facet
     * @param BitrixFacetDictionaryRelations $facetDictionaryRelations
     */
    public function __construct(Facet $facet, BitrixFacetDictionaryRelations $facetDictionaryRelations)
    {
        $this->facet = $facet;
        $this->facetDictionaryRelations = $facetDictionaryRelations;
    }

    /**
     * Устанавливает элементы индекса, которые выернула выборка для поля
     *
     * @param BitrixFacetIndexItem[] $facetItems
     * @return static
     */
    public function setFacetItems(array $facetItems)
    {
        $this->facetItems = [];
        foreach ($facetItems as $item) {
            if ($item instanceof BitrixFacetIndexItem) {
                $this->facetItems[] = $item;
            }
        }

        return $this;
    }

    /**
     * Возвращает значения, которые вернула выборка из фасетного индекса для текущего поля
     *
     * @return BitrixFacetIndexItem[]
     */
    public function getFacetItems(): array
    {
        return $this->facetItems;
    }

    /**
     * @return BitrixFacetDictionaryRelations
     */
    public function getFacetDictionaryRelations(): BitrixFacetDictionaryRelations
    {
        return $this->facetDictionaryRelations;
    }

    /**
     * @param int $dictionaryValueId
     * @return string|null
     */
    public function getFacetDictionaryValue(int $dictionaryValueId): ?string
    {
        return $this->getFacetDictionaryRelations()->getDictionaryRelationValue($dictionaryValueId);
    }

    /**
     * Если поле является свойством с типом "S", то составит массив реальных значений,
     * которые вернула выборка из фасетного индекса для этого поля.
     * Эти значения хранятся в специальном справочнике "b_iblock_{{iblockId}}_index_val".
     *
     * @return array
     */
    public function getItemsDictionaryValues(): array
    {
        $result = [];
        foreach ($this->getFacetItems() as $item) {
            if (!$item->getValue() || !$item->isDictionaryType()) {
                continue;
            }
            $dictValue = $this->getFacetDictionaryValue($item->getValue()) ?? '';
            if ($dictValue !== '') {
                $result[$item->getValue()] = $dictValue;
            }
        }

        return $result;
    }

    /**
     * Если поле не относится к свойству с типом "S", то составит массив значений,
     * которые вернула выборка из фасетного индекса для этого поля.
     * Эти значения хранятся непосредственно в "b_iblock_{{iblockId}}_index".
     *
     * @return array
     */
    public function getItemsValues(): array
    {
        $result = [];
        foreach ($this->getFacetItems() as $item) {
            if ($item->isDictionaryType()) {
                continue;
            }
            $result[] = $item->getValue();
        }

        return $result;
    }

    /**
     * Если поле является числовым или относится к ценам, то составит массив значений из максимального
     * и минимального значений, которые вернула выборка из фасетного индекса для этого поля.
     * Вариантов значений может быть несколько: по исходной выборке и по уточненной выборке с наложенными фильтрами.
     *
     * @return array
     */
    public function getItemsRangeValues(): array
    {
        $result = [];
        foreach ($this->getFacetItems() as $item) {
            if ($item->isDictionaryType()) {
                continue;
            }

            $result[] = [
                // Если не задан тип значения, то считаем, что это результат для исходной выборки
                'valueType' => $item->getExtraDataValue('VALUE_TYPE') ?? DisplayTypeEnum::RANGE_VALUE_TYPE_INITIAL,
                'min' => $item->getMinValueNum(),
                'max' => $item->getMaxValueNum(),
            ];
        }

        return $result;
    }

    /**
     * @param string $dictValue
     * @return int
     */
    public function getFacetDictionaryIdByValue(string $dictValue): int
    {
        return (int)array_search($dictValue, $this->getFacetDictionaryRelations()->getDictionaryRelations(), false);
    }

    /**
     * Возвращает модель результата выборки по значению для свойств с типом "S"
     *
     * @param string $dictValue
     * @return BitrixFacetIndexItem|null
     */
    public function getFacetItemByDictValue(string $dictValue): ?BitrixFacetIndexItem
    {
        $dictId = $this->getFacetDictionaryIdByValue($dictValue);
        if ($dictId) {
            foreach ($this->getFacetItems() as $item) {
                if ($item->isDictionaryType() && $item->getValue() === $dictId) {
                    return $item;
                }
            }
        }

        return null;
    }

    /**
     * Ищет модель результата выборки по полю VALUE
     *
     * @param int $value
     * @return BitrixFacetIndexItem|null
     */
    public function getFacetItemByValue(int $value): ?BitrixFacetIndexItem
    {
        foreach ($this->getFacetItems() as $item) {
            if ($item->getValue() === $value) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Ищет модель результата выборки по полям MIN_VALUE_NUM и MAX_VALUE_NUM
     *
     * @param float $value
     * @param string $valueType
     * @return BitrixFacetIndexItem|null
     */
    public function getFacetItemByNum(float $value, string $valueType = ''): ?BitrixFacetIndexItem
    {
        foreach ($this->getFacetItems() as $item) {
            if ($value >= $item->getMinValueNum() && $value <= $item->getMaxValueNum()) {
                if ($valueType === '' || $item->getExtraDataValue('VALUE_TYPE') === $valueType) {
                    return $item;
                }
            }
        }

        return null;
    }
}

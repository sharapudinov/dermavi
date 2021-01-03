<?php

namespace App\Filtration\Model;

use Bitrix\Iblock\PropertyIndex\Storage;

/**
 * Class BitrixFacetIndexItem
 * Модель, получаемая в результате выполнения запроса Facet::query()
 *
 * @package App\Filtration\Model
 */
class BitrixFacetIndexItem
{
    /** @var array */
    protected $fields = [];

    /** @var array */
    private $extraData = [];

    /** @var bool */
    private $propertyType;

    /** @var bool */
    private $dictionaryType = false;

    /** @var int */
    private $propertyId;

    /** @var int */
    private $priceId;

    /**
     * @param array $fields
     * @return static
     */
    public static function createFromArray(array $fields = [])
    {
        return (new static())->setFields($fields);
    }

    /**
     * @return array
     */
    protected function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     * @return static
     */
    protected function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return int
     */
    public function getFacetId(): int
    {
        return (int)($this->fields['FACET_ID'] ?? 0);
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return (int)($this->fields['VALUE'] ?? 0);
    }

    /**
     * @return float
     */
    public function getMinValueNum(): float
    {
        return (float)($this->fields['MIN_VALUE_NUM'] ?? 0);
    }

    /**
     * @return float
     */
    public function getMaxValueNum(): float
    {
        return (float)($this->fields['MAX_VALUE_NUM'] ?? 0);
    }

    /**
     * @return float
     */
    public function getValueFracLen(): float
    {
        return (float)($this->fields['VALUE_FRAC_LEN'] ?? 0);
    }

    /**
     * @return int
     */
    public function getElementCount(): int
    {
        return (int)($this->fields['ELEMENT_COUNT'] ?? 0);
    }

    /**
     * @param int $elementCount
     * @return static
     */
    public function setElementCount(int $elementCount)
    {
        $this->fields['ELEMENT_COUNT'] = $elementCount;

        return $this;
    }

    /**
     * @param array $extraData
     * @return static
     */
    public function setExtraData(array $extraData)
    {
        $this->extraData = $extraData;

        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function setExtraDataValue(string $name, $value)
    {
        $this->extraData[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getExtraDataValue(string $name)
    {
        return $this->extraData[$name] ?? null;
    }

    /**
     * @return bool
     */
    public function isPropertyType(): bool
    {
        if ($this->propertyType === null) {
            $this->propertyType = Storage::isPropertyId($this->getFacetId());
        }

        return $this->propertyType;
    }

    /**
     * Хранится ли реальное значение в специальном справочнике фасетного индеска
     *
     * @return bool
     */
    public function isDictionaryType(): bool
    {
        return $this->dictionaryType;
    }

    /**
     * @param bool $dictionaryType
     * @return static
     */
    public function setDictionaryType(bool $dictionaryType)
    {
        $this->dictionaryType = $dictionaryType;

        return $this;
    }

    /**
     * @return int
     */
    public function getPropertyId(): int
    {
        if ($this->propertyId === null) {
            $this->propertyId = $this->isPropertyType() ? Storage::facetIdToPropertyId($this->getFacetId()) : 0;
        }

        return $this->propertyId;
    }

    /**
     * @return int
     */
    public function getPriceId(): int
    {
        if ($this->priceId === null) {
            $this->priceId = !$this->isPropertyType() ? Storage::facetIdToPriceId($this->getFacetId()) : 0;
        }

        return $this->priceId;
    }
}

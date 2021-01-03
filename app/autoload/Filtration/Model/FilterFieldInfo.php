<?php

namespace App\Filtration\Model;

use App\Filtration\Enum\DisplayTypeEnum;

/**
 * Class FilterFieldInfo
 * Параметры поля, используемого в фильтрах
 *
 * @package App\Filtration\Model
 */
class FilterFieldInfo
{
    /** @var array */
    protected $fields = [];

    /** @var bool */
    protected $visible = true;

    /**
     * @param array $fields
     * @return static
     */
    public static function createFromArray(array $fields = [])
    {
        return (new static())->setFields(
            [
                'NAME' => $fields['NAME'] ?? '',
                'CODE' => $fields['CODE'] ?? '',
                'DISPLAY_TYPE' => $fields['DISPLAY_TYPE'] ?? '',
                'FILTER_HINT' => $fields['FILTER_HINT'] ?? '',
                'DISPLAY_EXPANDED' => $fields['DISPLAY_EXPANDED'] ?? '',
            ]
        );
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     * @return static
     */
    public function setVisible(bool $visible)
    {
        $this->visible = $visible;

        return $this;
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
     * @return string
     */
    public function getName(): string
    {
        return (string)($this->fields['NAME'] ?? '');
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName(string $name)
    {
        $this->fields['NAME'] = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string)($this->fields['CODE'] ?? '');
    }

    /**
     * @param string $code
     * @return static
     */
    public function setCode(string $code)
    {
        $this->fields['CODE'] = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayType(): string
    {
        return (string)($this->fields['DISPLAY_TYPE'] ?? '');
    }

    /**
     * @param string $displayType
     * @return static
     */
    public function setDisplayType(string $displayType)
    {
        $this->fields['DISPLAY_TYPE'] = $displayType;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilterHint(): string
    {
        return (string)($this->fields['FILTER_HINT'] ?? '');
    }

    /**
     * @param string $filterHint
     * @return static
     */
    public function setFilterHint(string $filterHint)
    {
        $this->fields['FILTER_HINT'] = $filterHint;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDisplayExpanded(): bool
    {
        return ($this->fields['DISPLAY_EXPANDED'] ?? 'Y') !== 'N';
    }

    /**
     * @param bool $displayExpanded
     * @return static
     */
    public function setDisplayExpanded(bool $displayExpanded)
    {
        $this->fields['DISPLAY_EXPANDED'] = $displayExpanded ? 'Y' : 'N';

        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayTypeProcessed(): string
    {
        return DisplayTypeEnum::convertFromBitrixValue($this->getDisplayType());
    }
}

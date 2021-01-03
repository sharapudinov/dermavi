<?php

namespace App\Filtration\Dto;

use JsonSerializable;

/**
 * Class FieldVariantDto
 *
 * @package App\Filtration\Dto
 */
class FieldVariantDto implements JsonSerializable
{
    /** @var string Минимально возможное значение диапазона */
    public const RANGE_CODE_MIN = 'min';

    /** @var string Максимальное возможное значение диапазона */
    public const RANGE_CODE_MAX = 'max';

    /** @var string Уточненное минимально возможное значение диапазона после наложения фильтров */
    public const RANGE_CODE_MIN_FILTERED = 'min_filtered';

    /** @var string Уточненное максимально возможное значение диапазона после наложения фильтров */
    public const RANGE_CODE_MAX_FILTERED = 'max_filtered';

    /** @var string Выбранное значение "от" */
    public const RANGE_CODE_FROM = 'from';

    /** @var string Выбранное значение "до" */
    public const RANGE_CODE_TO = 'to';

    /**
     * Значение варианта для вывода
     *
     * @var string
     */
    protected $name = '';

    /**
     * Значение варианта для запроса фильтрации
     *
     * @var string
     */
    protected $value = '';

    /**
     * Url для установки фильтра
     *
     * @var string
     */
    protected $url = '';

    /**
     * Название поля для запроса фильтрации
     *
     * @var string
     */
    protected $requestName = '';

    /**
     * Код значения для range-поля
     * min|max|from|to
     *
     * @var string
     */
    protected $rangeCode = '';

    /**
     * Количество документов с этим значением.
     * Null - количество не определено
     *
     * @var int|null
     */
    protected $docCount;

    /**
     * Выбран ли вариант
     *
     * @var bool
     */
    protected $selected = false;

    /**
     * @var int
     */
    protected $sort = 500;

    /**
     * Доступен ли вариант
     *
     * @var bool
     */
    protected $enabled = true;

    /**
     * Описание значения
     *
     * @var string
     */
    protected $description = '';

    /**
     * Селектор варианта в html
     *
     * @var string
     */
    protected $htmlSelector = '';

    /**
     * Дополнительне несистемные данные
     *
     * @var array
     */
    protected $extra = [];

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return static
     */
    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     * @return static
     */
    public function setSelected(bool $selected)
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDocCount(): ?int
    {
        return $this->docCount;
    }

    /**
     * @param int $docCount
     * @return static
     */
    public function setDocCount(int $docCount)
    {
        $this->docCount = $docCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     * @return static
     */
    public function setSort(int $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return static
     */
    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return static
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getRequestName(): string
    {
        return $this->requestName;
    }

    /**
     * @param string $requestName
     * @return static
     */
    public function setRequestName(string $requestName)
    {
        $this->requestName = $requestName;

        return $this;
    }

    /**
     * @return string
     */
    public function getRangeCode(): string
    {
        return $this->rangeCode;
    }

    /**
     * @param string $rangeCode
     * @return static
     */
    public function setRangeCode(string $rangeCode)
    {
        $this->rangeCode = $rangeCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlSelector(): string
    {
        return $this->htmlSelector;
    }

    /**
     * @param string $htmlSelector
     * @return static
     */
    public function setHtmlSelector(string $htmlSelector)
    {
        $this->htmlSelector = $htmlSelector;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    /**
     * @param array $extra
     * @return static
     */
    public function setExtra(array $extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * @param string $code
     * @return mixed|null
     */
    public function getExtraValue(string $code)
    {
        return $this->extra[$code] ?? null;
    }

    /**
     * @param string $code
     * @param mixed $value
     * @return static
     */
    public function setExtraValue(string $code, $value)
    {
        $this->extra[$code] = $value;

        return $this;
    }

    /**
     * @param string $code
     * @return static
     */
    public function unsetExtraValue(string $code)
    {
        if (isset($this->extra[$code])) {
            unset($this->extra[$code]);
        }

        return $this;
    }
}

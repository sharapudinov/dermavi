<?php

namespace App\Filtration\Dto;

use App\Filtration\Collection\VariantDtoCollection;
use JsonSerializable;

/**
 * Class FieldDto
 *
 * @package App\Filtration\Dto
 */
class FieldDto implements JsonSerializable
{
    /**
     * Заголовок поля
     *
     * @var string
     */
    protected $title = '';

    /**
     * Код поля
     *
     * @var string
     */
    protected $code = '';

    /**
     * Флаг видимости поля
     *
     * @var bool
     */
    protected $visible = true;

    /**
     * Показывать развернутым
     *
     * @var bool
     */
    protected $displayExpanded = true;

    /**
     * Тип поля
     * range|checkbox|radiobutton|select
     *
     * @var string
     */
    protected $displayType = '';

    /**
     * Описание поля
     *
     * @var string
     */
    protected $description = '';

    /**
     * @var VariantDtoCollection|FieldVariantDto[]
     */
    protected $variants;

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
    public function getTitle(): string
    {
        return (string)$this->title;
    }

    /**
     * @param string $title
     * @return static
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string)$this->code;
    }

    /**
     * @param string $code
     * @return static
     */
    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return VariantDtoCollection|FieldVariantDto[]
     */
    public function getVariants(): VariantDtoCollection
    {
        if ($this->variants === null) {
            $this->variants = new VariantDtoCollection();
        }

        return $this->variants;
    }

    /**
     * @param VariantDtoCollection $variants
     * @return static
     */
    public function setVariants(VariantDtoCollection $variants)
    {
        $this->variants = $variants;

        return $this;
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
     * @return bool
     */
    public function isDisplayExpanded(): bool
    {
        return $this->displayExpanded;
    }

    /**
     * @param bool $displayExpanded
     * @return static
     */
    public function setDisplayExpanded(bool $displayExpanded)
    {
        $this->displayExpanded = $displayExpanded;

        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayType(): string
    {
        return $this->displayType;
    }

    /**
     * @param string $displayType
     * @return static
     */
    public function setDisplayType(string $displayType)
    {
        $this->displayType = $displayType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
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
}

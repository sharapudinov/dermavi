<?php

namespace App\Seo\GlobalSiteTag;

/**
 * Класс описывающий отправляемое в Gtag событие
 *
 * Class GlobalSiteTagEvent
 * @package App\Seo\GlobalSiteTag
 */
class GlobalSiteTagEvent
{
    /** @var string */
    private $name;

    /** @var string */
    private $value;

    /** @var array */
    private $items;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
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
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return self
     */
    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }
}

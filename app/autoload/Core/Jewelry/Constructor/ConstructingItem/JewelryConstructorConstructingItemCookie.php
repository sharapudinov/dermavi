<?php

namespace App\Core\Jewelry\Constructor\ConstructingItem;

/**
 * Class JewelryConstructorConstructingItemCookie
 *
 * @package App\Core\Jewelry\Constructor\ConstructingItem
 */
class JewelryConstructorConstructingItemCookie
{
    /** @var string $link Ссылка на последнее состояние в конструкторе */
    private $link;

    /**
     * Returns json of class instance
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode(get_object_vars($this));
    }

    /**
     * Записывает в объект ссылку на последнее состояние в конструкторе
     *
     * @param string $link Ссылка
     *
     * @return JewelryConstructorConstructingItemCookie
     */
    public function setLink(string $link): self
    {
        $this->link = $link;
        return $this;
    }
}

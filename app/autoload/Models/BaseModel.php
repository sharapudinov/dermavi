<?php

namespace App\Models;

use Arrilot\BitrixModels\Models\ElementModel;

/**
 * Class BaseD7Model
 *
 * @package App\Models
 */
class BaseModel extends ElementModel
{
    /** @var string Символьный код инфоблока */
    const IBLOCK_CODE = '';

    /** @var bool Признак наличия в корзине */
    private $inBasket = false;

    /**
     * Возвращает идентификатор инфоблока.
     *
     * @return int
     */
    public static function iblockID(): int
    {
        return (int)iblock_id(static::IBLOCK_CODE);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this['ID'];
    }

    /**
     * Возвращает символьный код.
     *
     * @return string
     */
    public function getCode(): string
    {
        return (string)$this['CODE'];
    }

    /**
     * Внешний код
     * @return string
     */
    public function getExternalId(): string
    {
        return (string)$this['XML_ID'];
    }

    /**
     * Id подраздела элемента
     * @return mixed
     */
    public function getSectionId()
    {
        return $this['IBLOCK_SECTION_ID'];
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this['ACTIVE'] === 'Y';
    }

    /**
     * @param bool $inBasket
     */
    public function setInBasket(bool $inBasket)
    {
        $this->inBasket = $inBasket;
    }

    /**
     * @return bool
     */
    public function getInBasket()
    {
        return $this->inBasket;
    }
}

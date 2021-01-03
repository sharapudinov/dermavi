<?php

namespace App\Core\Sale\Entity;

use Bitrix\Sale\Services\PaySystem\Restrictions\Manager as RestrictionsManager;

/**
 * Класс для описания сущности "Платежная система"
 * Class PaySystem
 * @package App\Core\Sale\Entity
 */
class PaySystem
{
    /**
     * @var int - идентификатор
     */
    private $id;
    /**
     * @var string - символьный код
     */
    private $code;
    /**
     * @var array - ограничения (по службе доставки, типу плательщика и т.д. стандартные Битрикса)
     */
    private $restrictions;

    /**
     * PaySystem constructor.
     * @param array $paySystem
     */
    public function __construct(array $paySystem)
    {
        $this->id = $paySystem['ID'];
        $this->code = $paySystem['CODE'];
    }

    /**
     * Получить идентификатор
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Получить символьный код
     *
     * @return int
     */
    public function getCode(): string
    {
        return $this->code;
    }
    
    /**
     * Загрузить ограничения
     */
    public function loadRestrictions(): void
    {
        $this->restrictions = (array)RestrictionsManager::getRestrictionsList($this->getId());
    }
    
    /**
     * Получить ограничения
     *
     * @return array
     */
    public function getRestrictions(): array
    {
        $this->loadRestrictions();
        
        return $this->restrictions;
    }
}

<?php

namespace App\Core\BitrixProperty\Entity;

/**
 * Класс для описания сущности свойства
 * Class ListProperty
 * @package App\Core\BitrixProperty\Entity
 */
class Property
{
    /** @var int $propertyId - Идентификатор свойства */
    private $propertyId;

    /** @var int $variantId - Идентификатор нужного варианта свойства */
    private $variantId;

    /**
     * Property constructor.
     * @param array $property
     */
    public function __construct(array $property, array $variant = null)
    {
        $this->propertyId = $property['ID'];
        if ($variant) {
            $this->variantId = $variant['ID'];
        }
    }
    
    /**
     * Получаем идентификатор свойства
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->getPropertyId();
    }

    /**
     * Получаем идентификатор свойства
     *
     * @return int
     */
    public function getPropertyId(): int
    {
        return $this->propertyId;
    }

    /**
     * Получает идентификатор варианта свойства
     *
     * @return int
     */
    public function getVariantId(): int
    {
        return $this->variantId;
    }
}

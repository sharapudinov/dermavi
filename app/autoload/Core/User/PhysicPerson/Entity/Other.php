<?php

namespace App\Core\User\PhysicPerson\Entity;

use stdClass;

/**
 * Класс для описания сущности "Дополнительное средство связи"
 * Class Other
 * @package App\Core\User\PhysicPerson\Entity
 */
class Other
{
    /** @var string|null $Type - Тип связи */
    private $Type;

    /** @var string|null $Value - Значение */
    private $Value;

    public function __construct(stdClass $object = null)
    {
        if ($object) {
            $this->Type = $object->Type;
            $this->Value = $object->Value;
        } else {
            $this->Type = '';
            $this->Value = '';
        }
    }

    /**
     * Получаем тип связи (ключ)
     *
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->Type;
    }

    /**
     * Получаем тип связи (значение)
     *
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->Value;
    }
}

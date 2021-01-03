<?php

namespace App\Core\Sale\Entity;

/**
 * Класс для описания сущности "Тип плательщика"
 * Class PersonType
 * @package App\Core\Sale\Entity
 */
class PersonType
{
    /** @var int $personTypeId - Идентификатор типа плательщика */
    private $personTypeId;

    /**
     * PersonType constructor.
     * @param array $personType
     */
    public function __construct(array $personType)
    {
        $this->personTypeId = $personType['ID'];
    }

    /**
     * Получаем идентификатор типа плательщика
     *
     * @return int
     */
    public function getPersonTypeId(): int
    {
        return $this->personTypeId;
    }
}

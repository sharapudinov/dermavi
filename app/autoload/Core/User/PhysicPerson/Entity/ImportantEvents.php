<?php

namespace App\Core\User\PhysicPerson\Entity;

/**
 * Класс для описания сущности "Важное событие"
 * Class ImportantEvents
 * @package App\Core\User\PhysicPerson\Entity
 */
class ImportantEvents
{
    /** @var string|null $Date - Дата в формате YYYY-MM-DD */
    private $Date;

    /** @var string|null $Comment - Комментарий */
    private $Comment;

    public function __construct()
    {

    }

    /**
     * Получаем дату
     *
     * @return null|string
     */
    public function getDate(): ?string
    {
        return $this->Date;
    }

    /**
     * Получаем комментарий
     *
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->Comment;
    }
}

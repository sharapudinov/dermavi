<?php

namespace App\Core\User\PhysicPerson\Entity;

use App\Models\User;
use stdClass;

/**
 * Класс для описания сущности "Подписка на рассылки Email"
 * Class BulkEmail
 * @package App\Core\User\PhysicPerson\Entity
 */
class BulkEmail
{
    /** @var string|null $Id - Идентификатор подписки */
    private $Id;

    /**
     * Записывает идентификатор подписки
     *
     * @param $id Идентификатор подписки
     *
     * @return void
     */
    public function setId($id): void
    {
        $this->Id = $id;
    }

    /**
     * Получаем идентификатор подписки
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->Id;
    }
}

<?php

namespace App\Core\User\PhysicPerson\Entity;

use App\Helpers\GenderHelper;
use App\Models\User;
use stdClass;

/**
 * Класс для описания сущности "Дополнительные данные"
 * Class OtherData
 * @package App\Core\User\PhysicPerson\Entity
 */
class OtherData
{
    /** @var string|null $FamilyStatus - Семейное положение */
    private $FamilyStatus;

    /** @var string|null $WeddingDate - Дата свадьбы */
    private $WeddingDate;

    /** @var array|null $ImportantEvents - Массив важных событий */
    private $ImportantEvents;

    /**
     * Заполняет объект данными на основе ответа из crm
     *
     * @param stdClass $data - Объект с данными из crm
     *
     * @return OtherData
     */
    public function setFromCrm(stdClass $data): self
    {
        $this->FamilyStatus = $data->FamilyStatus;
        $this->WeddingDate = $data->WeddingDate;
        $this->ImportantEvents = $data->ImportantEvents;

        return $this;
    }

    /**
     * Заполняет объект данными на основе модели пользователя
     *
     * @param User $user - Модель пользователя
     *
     * @return OtherData
     */
    public function setFromUser(User $user): self
    {
        $this->FamilyStatus = GenderHelper::getFamilyStatusByAppeal($user->getAppeal());
        return $this;
    }

    /**
     * Получаем семейное положение
     *
     * @return null|string
     */
    public function getFamilyStatus(): ?string
    {
        return $this->FamilyStatus;
    }

    /**
     * Получаем дату свадьбы
     *
     * @return null|string
     */
    public function getWeddingDate(): ?string
    {
        return $this->WeddingDate;
    }

    /**
     * Получаем важные события
     *
     * @return array|null
     */
    public function getImportantEvents(): ?array
    {
        return $this->ImportantEvents;
    }
}

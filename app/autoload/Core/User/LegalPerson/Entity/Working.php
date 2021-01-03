<?php

namespace App\Core\User\LegalPerson\Entity;

use App\Models\HL\CompanyActivity;
use stdClass;

/**
 * Класс для описания сущности "Деятельность клиента"
 * Class Working
 * @package App\Core\User\LegalPerson\Entity
 */
class Working
{
    /** @var string|null $Id - Идентификатор деятельности клиента */
    private $Id;

    /**
     * Записывает в свойство класса идентификатор сферы деятельности
     *
     * @param string $id - Идентификатор сферы деятельности в crm
     *
     * @return self
     */
    public function setId(string $id): self
    {
        $this->Id = $id;
        return $this;
    }

    /**
     * Заполняет объект данными из CRM
     *
     * @param stdClass|null $working - Объект, описывающий деятельность клиента из CRM
     *
     * @return Working
     */
    public function setIdFromCrm(?stdClass $working): self
    {
        if ($working) {
            $activity = CompanyActivity::filter(['UF_CRM_ID' => $working->guid])->first();
            if ($activity) {
                $this->Id = $activity->getId();
            }
        }

        return $this;
    }

    /**
     * Получаем идентификатор деятельности клиента
     *
     * @return string|null
     */
    public function getGuid(): ?string
    {
        return $this->Id;
    }

    /**
     * Возвращает идентификатор сферы деятельности компании в БД ИМ
     *
     * @return int|null
     */
    public function getCompanyActivityId(): ?int
    {
        /** @var CompanyActivity $activity - Модель сферы деятельности компании */
        $activity = CompanyActivity::filter(['UF_CRM_ID' => $this->getGuid()])->first();
        return $activity ? $activity->getId() : null;
    }
}

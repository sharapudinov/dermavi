<?php

namespace App\Core\User\LegalPerson\Entity;

use App\Models\User;
use stdClass;

/**
 * Класс для описания сущности "Контакты"
 * Class Contact
 * @package App\Core\User\LegalPerson\Entity
 */
class Contact
{
    /** @var string|null $CrmId - ID CRM */
    private $CrmId;

    /** @var string|null $Surname - Фамилия */
    private $Surname;

    /** @var string|null $GivenName - Имя */
    private $GivenName;

    /** @var string|null $MiddleName - Отчество */
    private $MiddleName;

    /** @var string|null $JobTitle - Должность */
    private $JobTitle;

    /** @var string|null $Email - Email */
    private $Email;

    /** @var string|null $Phone - Рабочий телефон */
    private $Phone;

    /** @var string|null $MobilePhone - Мобильный телефон */
    private $MobilePhone;

    /**
     * Записывает в объект данные из CRM
     *
     * @param stdClass|null $contact - Объект, описывающий контактные данные
     *
     * @return Contact
     */
    public function setFromCrm(?stdClass $contact): self
    {
        if ($contact) {
            $this->CrmId = $contact->Contact->CrmId;
            $this->Surname = $contact->Contact->Surname;
            $this->GivenName = $contact->Contact->GivenName;
            $this->MiddleName = $contact->Contact->MiddleName;
            $this->JobTitle = $contact->Contact->JobTitle;
            $this->Email = $contact->Contact->Email;
            $this->Phone = $contact->Contact->Phone;
            $this->MobilePhone = $contact->Contact->MobilePhone;
        }

        return $this;
    }

    /**
     * Записывает в объект данные из БД ИМ
     *
     * @param User $user - Модель пользователя
     *
     * @return Contact
     */
    public function setFromDatabase(User $user): self
    {
        $this->CrmId = $user->contact->getCrmId();
        $this->GivenName = $user->getName();
        $this->JobTitle = $user->getCompanyPosition();
        $this->Email = $user->getEmail();
        $this->Phone = $user->getPhone();

        return $this;
    }

    /**
     * Получаем ID CRM
     *
     * @return null|string
     */
    public function getCrmId(): ?string
    {
        return $this->CrmId;
    }

    /**
     * Получаем имя
     *
     * @return string|null
     */
    public function getGivenName(): ?string
    {
        return $this->GivenName;
    }

    /**
     * Получаем фамилию
     *
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->Surname;
    }

    /**
     * Получаем отчество
     *
     * @return null|string
     */
    public function getMiddleName(): ?string
    {
        return $this->MiddleName;
    }

    /**
     * Получаем должность
     *
     * @return string|null
     */
    public function getJobTitle(): ?string
    {
        return $this->JobTitle;
    }

    /**
     * Получаем email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->Email;
    }

    /**
     * Получаем рабочий телефон
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->Phone;
    }

    /**
     * Получаем мобильный телефон
     *
     * @return string|null
     */
    public function getMobilePhone(): ?string
    {
        return $this->MobilePhone;
    }
}

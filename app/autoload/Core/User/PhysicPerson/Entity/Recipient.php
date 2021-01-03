<?php

namespace App\Core\User\PhysicPerson\Entity;

use App\Models\HL\Consignee;
use stdClass;

/**
 * Класс для описания сущности "Получатель"
 * Class Recipient
 * @package App\Core\User\PhysicPerson\Entity
 */
class Recipient
{
    /** @var string|null $ID - ID CRM */
    private $Id;

    /** @var string|null $Surname - Фамилия */
    private $Surname;

    /** @var string|null $GivenName - Имя */
    private $GivenName;

    /** @var string|null $MiddleName - Отчество */
    private $MiddleName;

    /** @var string|null $MobilePhone - Мобильный телефон */
    private $MobilePhone;

    /** @var string|null $RecipientDocumentKind - Вид документа */
    private $RecipientDocumentKind;

    /** @var string|null $RecipientDocumentNumber - Номер документа */
    private $RecipientDocumentNumber;

    /**
     * Заполняет объект данными из бд
     *
     * @param Consignee $consignee Модель грузополучателя
     *
     * @return Recipient
     */
    public function fillFromDatabase(Consignee $consignee): self
    {
        $this->Id = $consignee->getCrmId();
        $this->Surname = $consignee->getSurname();
        $this->GivenName = $consignee->getName();
        $this->MiddleName = $consignee->getMiddleName();
        $this->MobilePhone = $consignee->getPhone();
        $this->RecipientDocumentKind = $consignee->passportData->getType()->getXmlId();
        $this->RecipientDocumentNumber = $consignee->passportData->getNumber();

        return $this;
    }

    /**
     * Получаем ID CRM
     *
     * @return null|string
     */
    public function getID(): ?string
    {
        return $this->ID;
    }

    /**
     * Получаем фамилию
     *
     * @return null|string
     */
    public function getSurname(): ?string
    {
        return $this->Surname;
    }

    /**
     * Получаем имя
     *
     * @return null|string
     */
    public function getGivenName(): ?string
    {
        return $this->GivenName;
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
     * Получаем номер мобильного телефона
     *
     * @return null|string
     */
    public function getMobilePhone(): ?string
    {
        return $this->MobilePhone;
    }

    /**
     * Получаем вид документа
     *
     * @return null|string
     */
    public function getRecipientDocumentKind(): ?string
    {
        return $this->RecipientDocumentKind;
    }

    /**
     * Получаем номер документа
     *
     * @return null|string
     */
    public function getRecipientDocumentNumber(): ?string
    {
        return $this->RecipientDocumentNumber;
    }
}

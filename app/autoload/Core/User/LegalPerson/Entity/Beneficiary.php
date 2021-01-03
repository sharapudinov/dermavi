<?php

namespace App\Core\User\LegalPerson\Entity;

use App\Core\User\General\Entity\IdentityDocument;
use App\Models\HL\Beneficiary as BeneficiaryModel;

/**
 * Класс для описания сущности "Бенефициары"
 * Class Beneficiary
 * @package App\Core\User\LegalPerson\Entity
 */
class Beneficiary
{
    /** @var string|null $ID - ID CRM */
    private $CrmId;

    /** @var string|null $Surname - Фамилия */
    private $Surname;

    /** @var string|null $GivenName - Имя */
    private $GivenName;

    /** @var string|null $MiddleName - Отчество */
    private $MiddleName;

    /** @var string|null $BirthDate - Дата рождения */
    private $BirthDate;

    /** @var string|null $BirthCountry - Страна рождения (ALR-86) */
    private $BirthCountry;

    /** @var string|null $BirthCity - Город рождения */
    private $BirthCity;

    /** @var string|null $TaxNumder - Налоговый номер */
    private $TaxNumder;

    /** @var bool|null $IsPublicPerson - Публичное должностное лицо */
    private $IsPublicPerson;

    /**
     * @var string|null $RelationDegree - Степень родства
     * $IsPublicPerson === true ? обязательно
     */
    private $RelationDegree;

    /** @var string|null $Category - Категория */
    private $Category;

    /** @var string|null $JobTitle - Должность */
    private $JobTitle;

    /** @var IdentityDocument|null $IdentityDocument - Паспортные данные */
    private $IdentityDocument;

    public function fillFromCrm(BeneficiaryModel $beneficiary): self
    {
        $this->CrmId = $beneficiary->getCrmId();
        $this->Surname = $beneficiary->getSurname();
        $this->GivenName = $beneficiary->getName();
        $this->MiddleName = $beneficiary->getMiddleName();
        $this->BirthDate = $beneficiary->passportData->getBirthday('Y-m-d');
        $this->BirthCountry = $beneficiary->passportData->birthCountry->getCrmId();
        $this->BirthCity = $beneficiary->passportData->getBirthPlace();
        $this->TaxNumder = $beneficiary->getTaxNumber();
        $this->IsPublicPerson = $beneficiary->publicOfficial->getFullName() == $beneficiary->getFullName() ? 'Y' : 'N';
        $this->RelationDegree = $beneficiary->publicOfficial->getRelationDegree();
        $this->JobTitle = $beneficiary->publicOfficial->getPosition();
        $this->IdentityDocument = (new IdentityDocument())->fillFromDatabase($beneficiary->passportData);

        return $this;
    }

    /**
     * Получаем ID CRM
     *
     * @return null|string
     */
    public function getID(): ?string
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
     * Получаем дату рождения
     *
     * @return string|null
     */
    public function getBirthDate(): ?string
    {
        return $this->BirthDate;
    }

    /**
     * Получаем город рождения
     *
     * @return string|null
     */
    public function getBirthCity(): ?string
    {
        return $this->BirthCity;
    }

    /**
     * Получаем страну рождения
     *
     * @return string|null
     */
    public function getBirthCountry(): ?string
    {
        return $this->BirthCountry;
    }

    /**
     * Получаем ИНН
     *
     * @return string|null
     */
    public function getTaxNumber(): ?string
    {
        return $this->TaxNumder;
    }

    /**
     * Публичное ли лицо
     *
     * @return bool|null
     */
    public function isPublicPerson(): ?bool
    {
        return $this->IsPublicPerson;
    }

    /**
     * Проверяем степень родства
     *
     * @return string|null
     */
    public function getRelationDegree(): ?string
    {
        return $this->RelationDegree;
    }

    /**
     * Получаем категорию
     *
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->Category;
    }

    /**
     * Получаем паспорт
     *
     * @return IdentityDocument|null
     */
    public function getIdentityDocument(): ?IdentityDocument
    {
        return $this->IdentityDocument;
    }
}

<?php

namespace App\Core\User\General\Entity;

use App\Helpers\TTL;
use App\Models\HL\Country;
use App\Models\HL\PassportData;
use stdClass;

/**
 * Класс для описания сущности "Бенефициар.Паспортные данные"
 * Class IdentityDocument
 * @package App\Core\User\General\Entity
 */
class IdentityDocument
{
    /** @var string|null $Id - ID CRM */
    private $Id;

    /** @var string|null $Type - Вид документа */
    private $Type;

    /** @var string|null $Series - Серия */
    private $Series;

    /** @var string|null $Number */
    private $Number;

    /** @var string|null $IssueDate - Дата выдачи (Y-m-dTH:i:s) */
    private $IssueDate;

    /** @var string|null $StartDate - Дата начала действия (Y-m-dTH:i:s) */
    private $StartDate;

    /** @var string|null $ValidityDate - Дата окончания действия (Y-m-dTH:i:s) */
    private $ValidityDate;

    /** @var string|null $DocumentOrgan - Организация, выдавшая документ */
    private $DocumentOrgan;

    /** @var string|null $RegCountry - Страна регистрации */
    private $RegCountry;

    /** @var string|null $CityReg - Город регистрации */
    private $CityReg;

    /** @var string|null $Citizenship - Гражданство */
    private $Citizenship;

    /** @var string|null $MigrationCard - Миграционная карта */
    private $MigrationCard;

    /**
     * Заполняет объект данными из БД
     *
     * @param PassportData $passportData - Модель паспортных данных
     *
     * @return IdentityDocument
     */
    public function fillFromDatabase(PassportData $passportData): self
    {
        $this->Id = $passportData->getCrmId();
        $this->Type = $passportData->getType()->getXmlId();
        $this->Series = $passportData->getSeries();
        $this->Number = $passportData->getNumber();
        $this->IssueDate = $passportData->getIssueDate('Y-m-d');
        $this->StartDate = $passportData->getStartDate('Y-m-d');
        $this->ValidityDate = $passportData->getValidTo('Y-m-d');
        $this->DocumentOrgan = $passportData->getIssueOrganization();

        if ($passportData->getRegistrationCountry()) {
            $this->RegCountry = Country::cache(TTL::DAY)->getById($passportData->getRegistrationCountry())
                ->getCrmId();
        }

        $this->CityReg = $passportData->getRegisterCity();
        $this->Citizenship = $passportData->citizenship->getCrmId();
        $this->MigrationCard = $passportData->getMigrationCard();

        return $this;
    }

    /**
     * Заполняет объект данными из CRM
     *
     * @param stdClass $passportData Модель паспортных данных
     *
     * @return IdentityDocument
     */
    public function fillFromCrm(stdClass $passportData): self
    {
        $this->RegCountry = Country::cache(TTL::DAY)->filter(['UF_CRM_ID' => $passportData->RegCountry])
            ->first()
            ->getId();
        $this->Id = $passportData->Id;

        return $this;
    }

    /**
     * Получаем ID CRM
     *
     * @return null|string
     */
    public function getCrmId(): ?string
    {
        return $this->Id;
    }

    /**
     * Получаем тип документа
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->Type;
    }

    /**
     * Получаем серию документа
     *
     * @return string|null
     */
    public function getSeries(): ?string
    {
        return $this->Series;
    }

    /**
     * Получаем номер документа
     *
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->Number;
    }

    /**
     * Получаем дату выдачи
     *
     * @return string|null
     */
    public function getIssueDate(): ?string
    {
        return $this->IssueDate;
    }

    /**
     * Получаем дату начала действия
     *
     * @return string|null
     */
    public function getStartDate(): ?string
    {
        return $this->StartDate;
    }

    /**
     * Получаем дату окончания действия
     *
     * @return string|null
     */
    public function getValidityDate(): ?string
    {
        return $this->ValidityDate;
    }

    /**
     * Получаем орган, выдавший документ
     *
     * @return string|null
     */
    public function getDocumentOrgan(): ?string
    {
        return $this->DocumentOrgan;
    }

    /**
     * Получаем страну регистрации
     *
     * @return string|null
     */
    public function getRegCountry(): ?string
    {
        return $this->RegCountry;
    }

    /**
     * Получаем страну регистрации
     *
     * @return string|null
     */
    public function getCityReg(): ?string
    {
        return $this->CityReg;
    }

    /**
     * Получаем гражданство
     *
     * @return string|null
     */
    public function getCitizenship(): ?string
    {
        return $this->Citizenship;
    }

    /**
     * Получаем миграционную карту
     *
     * @return null|string
     */
    public function getMigrationCard(): ?string
    {
        return $this->MigrationCard;
    }
}

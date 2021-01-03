<?php

namespace App\Core\User\General\Entity;

use App\Models\HL\Bank;
use App\Models\HL\Company\Company;
use stdClass;

/**
 * Класс для описания сущности "Банковские реквизиты"
 * Class BankDetail
 * @package App\Core\User\LegalPerson\Entity
 */
class BankDetail
{
    /** @var string|null $Id - Идентификатор CRM */
    private $Id;

    /** @var string|null $Bank - Название банка */
    private $Bank;

    /** @var string|null $BankDetails - Расчетный счет */
    private $BankDetails;

    /** @var string|null $BIK - БИК */
    private $BIK;

    /** @var string|null $KC - К/С */
    private $KC;

    /** @var string|null $INN - ИНН */
    private $INN;

    /** @var string|null $KPP - КПП */
    private $KPP;

    /** @var string|null $OKPO - ОКПО */
    private $OKPO;

    /**
     * Заполняет объект данными из CRM
     *
     * @param stdClass|null $bank - Объект из CRM, описывающий банковские данные клиента
     *
     * @return BankDetail
     */
    public function setFromCrm(?stdClass $bank): self
    {
        if ($bank) {
            $this->Id = $bank->BankDetail->Id;
            $this->Bank = $bank->BankDetail->Bank;
            $this->BankDetails = $bank->BankDetail->BankDetails;
            $this->BIK = $bank->BankDetail->BIK;
            $this->KC = $bank->BankDetail->KC;
            $this->INN = $bank->BankDetail->INN;
            $this->KPP = $bank->BankDetail->KPP;
            $this->OKPO = $bank->BankDetail->OKPO;
        }

        return $this;
    }

    /**
     * Заполняет объект данными из БД ИМ
     *
     * @param Bank $bank Модель банковских данных пользователя
     *
     * @return BankDetail
     */
    public function setFromDatabase(Bank $bank): self
    {
        $this->Id = $bank->getCrmId();
        $this->Bank = $bank->getName();
        $this->BankDetails = $bank->getCheckingAccount();
        $this->BIK = $bank->getBik();
        $this->KC = $bank->getCorAccount();
        $this->INN = $bank->getTaxId();
        $this->KPP = $bank->getKPP();
        $this->OKPO = $bank->getOKPO();

        return $this;
    }

    /**
     * Возвращает идентификатор в crm записи
     *
     * @return string|null
     */
    public function getCrmId(): ?string
    {
        return $this->Id;
    }

    /**
     * Получаем название банка
     *
     * @return string|null
     */
    public function getBank(): ?string
    {
        return $this->Bank;
    }

    /**
     * Получаем расчетный счет банка
     *
     * @return string|null
     */
    public function getBankDetails(): ?string
    {
        return $this->BankDetails;
    }

    /**
     * Получаем БИК банка
     *
     * @return string|null
     */
    public function getBIK(): ?string
    {
        return $this->BIK;
    }

    /**
     * Получаем К/С банка
     *
     * @return string|null
     */
    public function getKC(): ?string
    {
        return $this->KC;
    }

    /**
     * Получаем ИНН банка
     *
     * @return string|null
     */
    public function getINN(): ?string
    {
        return $this->INN;
    }

    /**
     * Получаем КПП банка
     *
     * @return string|null
     */
    public function getKPP(): ?string
    {
        return $this->KPP;
    }

    /**
     * Получаем ОКПО банка
     *
     * @return string|null
     */
    public function getOKPO(): ?string
    {
        return $this->OKPO;
    }
}

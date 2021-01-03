<?php

namespace App\Core\User\LegalPerson\Entity;

use App\Core\User\CrmPersonEntityInterface;
use App\Core\User\General\Entity\Address;
use App\Core\User\General\Entity\BankDetail;
use App\Core\User\General\Entity\Document;
use App\Core\User\General\Entity\DeliveryData;
use App\Helpers\LanguageHelper;
use App\Models\HL\Company\CompanyType;
use App\Models\HL\UserPersonType;
use App\Models\User;
use stdClass;

/**
 * Класс для описания сущности "Юридическое лицо"
 * Class LegalEntity
 * @package App\Core\User\LegalPerson\Entity
 */
class LegalEntity implements CrmPersonEntityInterface
{
    /** @var string|null $CrmId - ID CRM */
    private $CrmId;

    /** @var bool $IsBuyAvailable - Доступна покупка */
    private $IsBuyAvailable;

    /** @var string $Name - Наименование (сокращ) */
    private $Name;

    /** @var string $NameRus - Наименование (рус) */
    private $NameRus;

    /** @var string $NameEng - Наименование (англ) */
    private $NameEng;

    /** @var bool $TypeClient - Тип клиента (true - юр лицо, false - индивидуальный предприниматель) */
    private $TypeClient;

    /** @var string|null $Status - Статус клиента */
    private $Status;

    /** @var string $Country - Страна регистрации (ALR-86) */
    private $Country;

    /** @var string $TaxNumber - Налоговый номер */
    private $TaxNumber;

    /** @var string $Login - Логин */
    private $Login;

    /** @var string $Password - Пароль */
    private $Password;

    /**
     * @var string|null $RegistrationNumber - Номер регистрации компании
     * $IsBuyAvailable === true ? обязателен
     */
    private $RegistrationNumber;

    /**
     * @var string|null $RegistrationDate - Дата регистрации компании (Y-m-dTH:i:s)
     * $IsBuyAvailable === true ? обязателен
     */
    private $RegistrationDate;

    /**
     * @var string|null $NumberCertificateOfAssay - Номер свидетельства пробирной палаты
     * $IsBuyAvailable === true ? обязателен
     */
    private $NumberCertificateOfAssay;

    /**
     * @var string|null $KPP - КПП
     * $IsBuyAvailable === true ? обязателен
     */
    private $KPP;

    /**
     * @var string|null $OKPO - ОКПО
     * $IsBuyAvailable === true ? обязателен
     */
    private $OKPO;

    /**
     * @var string|null $OKFS - ОКФС
     * $IsBuyAvailable === true ? обязателен
     */
    private $OKFS;

    /**
     * @var string|null $OKOPF - ОКОПФ
     * $IsBuyAvailable === true ? обязателен
     */
    private $OKOPF;

    /** @var Working|null $Working - Деятельность клиента */
    private $Working;

    /** @var BankDetail|null $BankDetail - Банковские реквизиты */
    private $BankDetail;

    /** @var DeliveryData|null $DeliveryData - Информация о доставке */
    private $DeliveryData;

    /** @var Address|null $Address - Адресные данные */
    private $Address;

    /** @var Contact|null $Contact - Контакты */
    private $Contact;

    /** @var Beneficiary|null $Beneficiary - Бенефициары */
    private $Beneficiary;

    /** @var Document|null $Document - Документы */
    private $Document;

    /**
     * Записывает в объект, описывающий сущность, данные на основе объекта пользователя из CRM
     *
     * @param stdClass $crmUser - Объект из CRM, описывающий пользователя
     *
     * @return CrmPersonEntityInterface
     */
    public function fillFromCrmUser(stdClass $crmUser): CrmPersonEntityInterface
    {
        $this->CrmId = $crmUser->CrmId;
        $this->IsBuyAvailable = $crmUser->IsBuyAvailable ?? false;
        $this->Name = $crmUser->Name;
        $this->NameEng = $crmUser->NameEng;
        $this->NameRus = $crmUser->NameRus;
        $this->Country = $crmUser->Country;
        $this->TaxNumber = $crmUser->TaxNumber;
        $this->Login = $crmUser->Login;
        $this->Password = $crmUser->Password;
        $this->RegistrationNumber = $crmUser->RegistrationNumber;
        $this->RegistrationDate = $crmUser->RegistrationDate;
        $this->NumberCertificateOfAssay = $crmUser->NumberCertificateOfAssay;
        $this->KPP = $crmUser->KPP;
        $this->OKPO = $crmUser->OKPO;
        $this->OKFS = $crmUser->OKFS;
        $this->OKOPF = $crmUser->OKOPF;
        $this->Contact = (new Contact())->setFromCrm($crmUser->Contact);
        $this->Working = (new Working())->setIdFromCrm($crmUser->Working);

        if ($crmUser->Address->Address) {
            $crmUser->Address->Address = is_array($crmUser->Address->Address)
                ? $crmUser->Address->Address
                : [$crmUser->Address->Address];
            foreach ($crmUser->Address->Address as $address) {
                $this->Address[] = (new Address())->setFromCrm($address);
            }
        }
        $this->BankDetail = (new BankDetail())->setFromCrm($crmUser->BankDetail);

        return $this;
    }

    /**
     * Записывает в объект, описывающий сущность, данные на основе модели пользователя из БД ИМ
     *
     * @param User $user - Модель, описывающая пользователя в БД ИМ
     * @param bool $fullData - Флаг того, что необходим полный набор данных (false используется при регистрации)
     *
     * @return CrmPersonEntityInterface
     */
    public function fillFromDatabaseUser(User $user, bool $fullData = true): CrmPersonEntityInterface
    {
        $this->CrmId = $user->getCrmId();
        $this->TypeClient = $user->company->type->getName() == CompanyType::LEGAL_ENTITY;
        $this->Login = $user->getEmail();

        $this->Name = $user->company->getName();
        $this->NameRus = $user->company->getFullName();
        $this->NameEng = $user->company->getFullName();
        $this->Country = $user->company->country->getCrmId();
        $this->TaxNumber = $user->company->getTaxNumber();

        $this->RegistrationNumber = $user->company->getOgrn();
        $this->RegistrationDate = $user->company->getRegisterDate('Y-m-d');
        $this->KPP = $user->company->getKpp();
        $this->OKPO = $user->company->getOkpo();

        if ($fullData) {
            if ($user->company->companyActivity->getCrmId()) {
                $this->Working[] = $user->company->companyActivity->getCrmId();
            }
            $this->BankDetail[] = (new BankDetail())->setFromDatabase($user->company->bank);

            $this->Address->Address[] = (new Address())->setFromUser($user);
            foreach ($user->deliveryAddresses as $deliveryAddress) {
                $this->Address->Address[] = (new Address())->setFromDeliveryAddress($deliveryAddress);
            }

            $this->Contact[] = (new Contact())->setFromDatabase($user);

            foreach ($user->personalInfoForm->beneficiaries as $beneficiary) {
                if ($beneficiary->personType->getName() == UserPersonType::PHYSIC_PERSON) {
                    $this->Beneficiary[] = (new Beneficiary())->fillFromCrm($beneficiary);
                }
            }

            foreach ($user->documents as $document) {
                $this->Document[] = (new Document())->setFromDatabaseDocument($document, $document->getFilesIds()[0]);
            }
        }

        return $this;
    }

    /**
     * Получает CrmId
     *
     * @return string
     */
    public function getCrmId(): string
    {
        return $this->CrmId;
    }

    /**
     * Задает пустое сокращенное имя юр лица
     *
     * @return LegalEntity
     */
    public function setEmptyName(): self
    {
        $this->Name = ' ';
        return $this;
    }

    /**
     * Задает название юр лица
     *
     * @param string $name - Название
     * @param User $user - Модель пользователя
     *
     * @return LegalEntity
     */
    public function setCompanyName(string $name, User $user): self
    {
        if ($user->company->address->country->isRussia()) {
            $this->NameRus = $name;
            $this->NameEng = ' ';
        } else {
            $this->NameEng = $name;
            $this->NameRus = ' ';
        }

        return $this;
    }

    /**
     * Задает логин пользователя
     *
     * @param string $login - Логин
     * @return LegalEntity
     */
    public function setLogin(string $login): self
    {
        $this->Login = $login;
        return $this;
    }

    /**
     * Задает пароль пользователя
     *
     * @param string $password - Пароль
     * @return LegalEntity
     */
    public function setPassword(string $password): self
    {
        $this->Password = $password;
        return $this;
    }

    /**
     * Задает страну регистрации юр лица
     *
     * @param string $country - Страна
     * @return LegalEntity
     */
    public function setCountry(string $country): self
    {
        $this->Country = $country;
        return $this;
    }

    /**
     * Задает ИНН
     *
     * @param string $taxNumber - ИНН
     * @return LegalEntity
     */
    public function setTaxNumber(string $taxNumber): self
    {
        $this->TaxNumber = $taxNumber;
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
     * Устанавливает пользователю флаг доступности покупки
     *
     * @return void
     */
    public function setIsBuyAvailable(): void
    {
        $this->IsBuyAvailable = true;
    }

    /**
     * Доступна ли покупка
     *
     * @return bool
     */
    public function isByuAvailable(): bool
    {
        return $this->IsBuyAvailable;
    }

    /**
     * Получаем наименование (сокр)
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * Получаем наименование на русском
     *
     * @return string
     */
    public function getNameRus(): string
    {
        return $this->NameRus;
    }

    /**
     * Получаем наименование на английском
     *
     * @return string
     */
    public function getNameEng(): string
    {
        return $this->NameEng;
    }

    /**
     * Получаем статус клиента
     *
     * @return null|string
     */
    public function getStatus(): ?string
    {
        return $this->Status;
    }

    /**
     * Получаем страну
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->Country;
    }

    /**
     * Получаем ИНН
     *
     * @return string
     */
    public function getTaxNumber(): string
    {
        return $this->TaxNumber;
    }

    /**
     * Получаем логин
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->Login;
    }

    /**
     * Получаем пароль
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->Password;
    }

    /**
     * Получаем номер регистрации компании
     *
     * @return null|string
     */
    public function getRegistrationNumber(): ?string
    {
        return $this->RegistrationNumber;
    }

    /**
     * Получаем дату регистрации
     *
     * @return null|string
     */
    public function getRegistrationDate(): ?string
    {
        return $this->RegistrationDate;
    }

    /**
     * Получаем номер свидетельства пробирной палаты
     *
     * @return null|string
     */
    public function getNumberCertificateOfAssay(): ?string
    {
        return $this->NumberCertificateOfAssay;
    }

    /**
     * Получаем КПП
     *
     * @return null|string
     */
    public function getKPP(): ?string
    {
        return $this->KPP;
    }

    /**
     * Получаем ОКПО
     *
     * @return null|string
     */
    public function getOKPO(): ?string
    {
        return $this->OKPO;
    }

    /**
     * Получаем ОКФС
     *
     * @return null|string
     */
    public function getOKFS(): ?string
    {
        return $this->OKFS;
    }

    /**
     * Получаем ОКОПФ
     *
     * @return null|string
     */
    public function getOKOPF(): ?string
    {
        return $this->OKOPF;
    }

    /**
     * Получаем деятельность клиента
     *
     * @return Working|null
     */
    public function getWorking(): ?Working
    {
        return $this->Working;
    }

    /**
     * Получаем банковские реквизиты
     *
     * @return BankDetail|null
     */
    public function getBankDetail(): ?BankDetail
    {
        return $this->BankDetail;
    }

    /**
     * Получаем информацию о доставке
     *
     * @return DeliveryData|null
     */
    public function getDeliveryData(): ?DeliveryData
    {
        return $this->DeliveryData;
    }

    /**
     * Получаем адресные данные
     *
     * @return array|Address[]
     */
    public function getAddress(): array
    {
        return $this->Address;
    }

    /**
     * Получаем Контакты
     *
     * @return Contact|null
     */
    public function getContact(): ?Contact
    {
        return $this->Contact;
    }

    /**
     * Получаем бенефициаров
     *
     * @return array|null
     */
    public function getBeneficiary(): ?array
    {
        return $this->Beneficiary;
    }

    /**
     * Получаем документы
     *
     * @return Document|null
     */
    public function getDocument(): ?Document
    {
        return $this->Document;
    }

    /**
     * Записывает в класс документ
     *
     * @param Document $document - Документ
     * @return void
     */
    public function setDocument(Document $document): void
    {
        $this->Document = $document;
    }
}

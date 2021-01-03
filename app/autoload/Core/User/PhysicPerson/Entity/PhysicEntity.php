<?php

namespace App\Core\User\PhysicPerson\Entity;

use App\Core\User\CrmPersonEntityInterface;
use App\Core\User\General\Entity\Address;
use App\Core\User\General\Entity\BankDetail;
use App\Core\User\General\Entity\DeliveryData;
use App\Core\User\General\Entity\Document;
use App\Core\User\General\Entity\IdentityDocument;
use App\Models\Auxiliary\CRM\Gender;
use App\Models\User;
use Exception;
use stdClass;

/**
 * Класс для описания сущности "Физическое лицо"
 * Class PhysicEntity
 * @package App\Core\User\PhysicPerson\Entity
 */
class PhysicEntity implements CrmPersonEntityInterface
{
    /** @var string - Идентификатор пустой страны рождения */
    private const EMPTY_BIRTH_COUNTRY_ID = '7083583b-6fde-4a1b-8175-fc8a768b4091';

    /** @var string|null $CrmId - ID CRM */
    private $CrmId;

    /** @var string $SalutationType Идентификатор типа обращения (Справочник) */
    private $SalutationType;

    /** @var string $Surname - Фамилия */
    private $Surname;

    /** @var string $GivenName - Имя */
    private $GivenName;

    /** @var string|null $MiddleName - Отчество */
    private $MiddleName;

    /** @var string $Login - Логин */
    private $Login;

    /** @var string $Gender - Пол */
    private $Gender;

    /** @var string $TaxNumber - ИНН */
    private $TaxNumber;

    /** @var string|null $BirthDate - Дата рождения */
    private $BirthDate;

    /** @var string $BirthCountry - Страна рождения */
    private $BirthCountry;

    /** @var string $BirthCity - Город рождения */
    private $BirthCity;

    /** @var bool $AvailablePurchaseUpTo100 - Доступна покупка до 100 000 рублей */
    private $AvailablePurchaseUpTo100;

    /** @var bool $AvailablePurchaseOver600 - Доступна покупка более 600 000 рублей */
    private $AvailablePurchaseOver600;

    /** @var bool $IsAuction - Доступно участие в аукционах */
    private $IsAuction;

    /** @var ContactData $ContactData - Контактная информация */
    private $ContactData;

    /** @var IdentityDocument|null $IdentityDocument - Паспортные данные */
    private $IdentityDocument;

    /** @var BankDetail|null $BankDetails - Массив данных по банковским реквизитам клиента */
    private $BankDetails;

    /** @var DeliveryData|null $DeliveryData - Информация о доставке */
    private $DeliveryData;

    /** @var Recipient|null $Recipient - Получатель */
    private $Recipient;

    /** @var null|array|Address[] $Address - Адресные данные (Необходимо помещать в дополнительный объект Address) */
    private $Address;

    /** @var OtherData|null $OtherData - Дополнительные данные */
    private $OtherData;

    /** @var array|null $Document - Документы */
    private $Document;

    /**
     * Записывает в объект адресные данные пользователя
     *
     * @param User $user - Модель пользователя
     *
     * @return void
     */
    private function setAddress(User $user): void
    {
        $this->Address->Address[] = (new Address())->setFromUser($user);
        foreach ($user->deliveryAddresses as $deliveryAddress) {
            $this->Address->Address[] = (new Address())->setFromDeliveryAddress($deliveryAddress);
        }
    }



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
        $this->Surname = $crmUser->Surname;
        $this->GivenName = $crmUser->Name;
        $this->MiddleName = $crmUser->MiddleName;
        $this->Gender = $crmUser->Gender;
        $this->TaxNumber = $crmUser->TaxNumber;
        $this->IdentityDocument = (new IdentityDocument())->fillFromCrm($crmUser->IdentityDocument->IdentityDocument);

        $crmUser->Address->Address = is_array($crmUser->Address->Address)
            ? $crmUser->Address->Address
            : [$crmUser->Address->Address];
        foreach ($crmUser->Address->Address as $address) {
            $this->Address[] = (new Address())->setFromCrm($address);
        }

        $this->OtherData = (new OtherData())->setFromCrm($crmUser->OtherData);

        $this->AvailablePurchaseUpTo100 = $crmUser->AvailablePurchaseUpTo100;
        $this->AvailablePurchaseUpTo600 = $crmUser->AvailablePurchaseUpTo600;
        $this->AvailablePurchaseOver600 = $crmUser->AvailablePurchaseOver600;
        $this->IsAuction = $crmUser->IsAuction;

        return $this;
    }

    /**
     * Записывает в объект, описывающий сущность, данные на основе модели пользователя из БД ИМ
     *
     * @param User $user - Модель, описывающая пользователя в БД ИМ
     * @param bool $fullData - Флаг того, что необходим полный набор данных (false используется при регистрации)
     *
     * @return static
     *
     * @throws Exception
     */
    public function fillFromDatabaseUser(User $user, bool $fullData = true): CrmPersonEntityInterface
    {
        $this->CrmId = $user->getCrmId();
        $this->SalutationType = $user->salutation->getXmlId();
        $this->Surname = $user->getSurname();
        $this->GivenName = $user->getName();
        $this->MiddleName = $user->getMiddleName();
        $this->Login = $user->getEmail();
        $this->Gender = Gender::getByAppeal($user->salutation)->getXmlId();

        $this->AvailablePurchaseUpTo100 = $user->isPurchaseAvailableUpTo100();
        $this->AvailablePurchaseOver600 = $user->isPurchaseAvailableOver100();
        $this->IsAuction = $user->canAuction();

        $this->setAddress($user);

        if ($fullData) {
            $this->TaxNumber = $user->getTaxNumber();
            $this->BirthDate = $user->passportData->getBirthday('Y-m-d');
            $this->BirthCountry = $user->passportData->birthCountry->getCrmId();
            $this->BirthCity = $user->passportData->getBirthPlace();
            $this->ContactData = (new ContactData())->fillFromDatabase($user);
            $this->IdentityDocument->IdentityDocument = (new IdentityDocument())->fillFromDatabase($user->passportData);

            foreach ($user->personalInfoForm->consignees as $consignee) {
                $this->Recipient[] = (new Recipient())->fillFromDatabase($consignee);
            }
            $this->OtherData = (new OtherData())->setFromUser($user);
            $this->Document->Document[] = (new Document())
                ->setFromDatabaseDocument($user->documents->first(), $user->documents->first()->getFilesIds()[0]);
            $this->Document->Document[] = (new Document())
                ->setFromDatabaseDocument($user->documents->first(), $user->documents->first()->getFilesIds()[1]);
        }

        return $this;
    }

    /**
     * Получает идентификатор пользователя в crm
     *
     * @return string
     */
    public function getCrmId(): string
    {
        return $this->CrmId;
    }

    /**
     * Возвращает логин пользователя
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->Login;
    }

    /**
     * Получает имя пользователя
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->GivenName;
    }

    /**
     * Получает фамилию
     *
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->Surname;
    }

    /**
     * Получает отчество
     *
     * @return string|null
     */
    public function getMiddleName(): ?string
    {
        return $this->MiddleName;
    }

    /**
     * Задает пустую страну рождения
     *
     * @return PhysicEntity
     */
    public function setEmptyBirthCountry(): self
    {
        $this->BirthCountry = self::EMPTY_BIRTH_COUNTRY_ID;
        return $this;
    }

    /**
     * Получает страну пользователя
     *
     * @return string|null
     */
    public function getBirthCountry(): ?string
    {
        return $this->BirthCountry;
    }

    /**
     * Задает город рождения
     *
     * @param string $city - Город
     * @return PhysicEntity
     */
    public function setBirthCity(string $city): self
    {
        $this->BirthCity = $city;
        return $this;
    }

    /**
     * Задает пол
     *
     * @param Gender $gender - Пол из справочника
     * @return PhysicEntity
     */
    public function setGender(Gender $gender): self
    {
        $this->Gender = $gender->getXmlId();
        return $this;
    }

    /**
     * Получает пол пользователя
     *
     * @return string
     */
    public function getGender(): string
    {
        return $this->Gender;
    }

    /**
     * Задает ИНН
     *
     * @param string $taxNumber - ИНН
     * @return PhysicEntity
     */
    public function setTaxNumber(string $taxNumber): self
    {
        $this->TaxNumber = $taxNumber;
        return $this;
    }

    /**
     * Получаем паспортные данные
     *
     * @return IdentityDocument|null
     */
    public function getIdentityDocument(): ?IdentityDocument
    {
        return $this->IdentityDocument;
    }

    /**
     * Получаем банковские реквизиты
     *
     * @return BankDetail|null
     */
    public function getBankDetails(): ?BankDetail
    {
        return $this->BankDetails;
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
     * Получаем информацию о получателе
     *
     * @return Recipient|null
     */
    public function getRecipient(): ?Recipient
    {
        return $this->Recipient;
    }

    /**
     * Получаем информацию по адресным данным
     *
     * @return array|Address[]
     */
    public function getAddress(): array
    {
        return $this->Address;
    }

    /**
     * Получаем дополнительные данные
     *
     * @return OtherData|null
     */
    public function getOtherData(): ?OtherData
    {
        return $this->OtherData;
    }

    /**
     * Получаем документы
     *
     * @return array|null
     */
    public function getDocument(): ?array
    {
        return $this->Document;
    }

    /**
     * Получает контактные данные
     *
     * @return ContactData|null
     */
    public function getContactData(): ?ContactData
    {
        return $this->ContactData;
    }

    /**
     * Устанавливает пользователю возможность покупки до 100 000 рублей
     *
     * @return PhysicEntity
     */
    public function setPurchaseUpTo100(): self
    {
        $this->AvailablePurchaseUpTo100 = true;
        return $this;
    }

    /**
     * Возвращает флаг доступности покупки до 100 000 рублей
     *
     * @return bool
     */
    public function isPurchaseUpTo100(): bool
    {
        return $this->AvailablePurchaseUpTo100;
    }

    /**
     * Устанавливает пользователю возможность покупки до 600 000 рублей
     *
     * @return PhysicEntity
     */
    public function setPurchaseUpTo600(): self
    {
        $this->AvailablePurchaseUpTo600 = true;
        return $this;
    }

    /**
     * Возвращает флаг доступности покупки до 600 000 рублей
     *
     * @return bool
     */
    public function isPurchaseUpTo600(): bool
    {
        return $this->AvailablePurchaseUpTo600;
    }

    /**
     * Устанавливает пользователю возможность покупки более 600 000 рублей
     *
     * @return PhysicEntity
     */
    public function setPurchaseOver600(): self
    {
        $this->AvailablePurchaseOver600 = true;
        return $this;
    }

    /**
     * Возвращает флаг доступности покупки более 600 000 рублей
     *
     * @return bool
     */
    public function isPurchaseOver600(): bool
    {
        return $this->AvailablePurchaseOver600;
    }

    /**
     * Устанавливает пользователю возможность участия в аукционах
     *
     * @return PhysicEntity
     */
    public function setIsAuction(): self
    {
        $this->IsAuction = true;
        return $this;
    }

    /**
     * Возвращает флаг возможности участия в аукционах
     *
     * @return bool
     */
    public function isAuction(): bool
    {
        return $this->IsAuction;
    }
}

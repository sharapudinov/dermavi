<?php

namespace App\Core\User\LegalPerson;

use App\Api\External\CRM\CrmClientAbstract;
use App\Api\External\CRM\User\CrmLegalPerson;
use App\Core\BitrixProperty\Property;
use App\Core\CRM\FieldsIntegrality\Address as AddressChecker;
use App\Core\CRM\FieldsIntegrality\BankDetails;
use App\Core\CRM\PersonalForm\LegalPerson\PersonalFormLegalEntity;
use App\Core\CRM\PersonalForm\LegalPerson\PersonalFormLegalIndividual;
use App\Core\User\User as UserCore;
use App\Core\User\Address\LegalPersonAddress;
use App\Core\User\CrmUserNotFoundException;
use App\Core\User\General\Entity\Address;
use App\Core\User\LegalPerson\Entity\LegalEntity;
use App\Core\User\UserInterface;
use App\Helpers\AddressHelper;
use App\Helpers\TTL;
use App\Models\HL\Address as AddressModel;
use App\Models\HL\AddressType;
use App\Models\HL\Bank;
use App\Models\HL\Company\Company;
use App\Models\HL\Company\CompanyType;
use App\Models\HL\Contact;
use App\Models\User;
use Bitrix\Main\Type\DateTime;
use Exception;
use Throwable;

/**
 * Класс для работы с юридическим лицом
 * Class LegalPersonUser
 * @package App\Core\User\LegalPerson
 */
class LegalPersonUser implements UserInterface
{
    /** @var string - Символьный код типа пользователя */
    public const PERSON_TYPE_CODE = 'LEGAL_ENTITY';

    /** @var LegalEntity $legalEntity - Объект, описывающий пользователя в CRM */
    private $legalEntity;

    /**
     * Генерирует массив, описывающий поля компании пользователя
     *
     * @param Address $address - Объект, описывающий адрес в CRM
     *
     * @return array
     */
    private function generateCompanyData(Address $address): array
    {
        return [
            'UF_NAME' => $this->legalEntity->getName(),
            'UF_ACTIVITY_ID' => $this->legalEntity->getWorking()->getCompanyActivityId(),
            'UF_EMAIL' => $address->getEmail(),
            'UF_PHONE' => $address->getPhone()
        ];
    }

    /**
     * Генерирует массив данных, описывающий юридический адрес
     *
     * @param Address $address - Объект из crm, описывающий адрес
     *
     * @return array|string[]
     */
    private function generateCompanyAddressData(Address $address): array
    {
        return [
            'UF_COUNTRY' => $address->getCountry(),
            'UF_ZIP' => $address->getZip(),
            'UF_REGION' => $address->getRegion(),
            'UF_CITY' => $address->getCity(),
            'UF_STREET' => $address->getStreet(),
            'UF_HOUSE' => $address->getHouse(),
            'UF_FLAT' => $address->getOffice(),
            'UF_CRM_ID' => $address->getCrmId()
        ];
    }

    /**
     * Генерирует массив данных, описывающий банковские реквизиты компании
     *
     * @return array|string[]
     */
    private function generateCompanyBankDetails(): array
    {
        return [
            'UF_NAME' => $this->legalEntity->getBankDetail()->getBank(),
            'UF_BIK' => $this->legalEntity->getBankDetail()->getBIK(),
            'UF_COR_ACCOUNT' => $this->legalEntity->getBankDetail()->getKC(),
            'UF_CHECK_ACCOUNT' => $this->legalEntity->getBankDetail()->getBankDetails(),
            'UF_KPP' => $this->legalEntity->getBankDetail()->getKPP(),
            'UF_OKPO' => $this->legalEntity->getBankDetail()->getOKPO(),
            'UF_TAX_ID' => $this->legalEntity->getBankDetail()->getINN(),
            'UF_CRM_ID' => $this->legalEntity->getBankDetail()->getCrmId()
        ];
    }

    /**
     * Генерирует массив, описывающий поля пользователя
     *
     * @param Address $address - Объект, описывающий адрес в CRM
     *
     * @return array
     */
    private function generateUserData(Address $address): array
    {
        return [
            'LOGIN' => $address->getEmail(),
            'EMAIL' => $address->getEmail(),
            'PERSONAL_PHONE' => $address->getPhone(),
            'UF_PURCHASE_OVER_600' => $this->legalEntity->isByuAvailable(),
            'UF_IS_APPROVING' => false,
            'UF_USER_ENTITY_TYPE' => Property::getUserTypeListPropertyValue(
                'UF_USER_ENTITY_TYPE',
                self::PERSON_TYPE_CODE
            )->getVariantId(),
            'UF_CRM_ID' => $this->legalEntity->getCrmId()
        ];
    }

    /**
     * Генерирует массив, описывающий контакт
     *
     * @return array|string[]
     */
    private function generateContactData(): array
    {
        return [
            'UF_CRM_ID' => $this->legalEntity->getContact()->getCrmId()
        ];
    }

    /**
     * Записывает в свойство объект, описывающий юр лицо
     *
     * @param LegalEntity $legalEntity - Объект, описывающий юр лицо
     *
     * @return LegalPersonUser
     */
    public function setUser(LegalEntity $legalEntity): self
    {
        $this->legalEntity = $legalEntity;
        return $this;
    }

    /**
     * Регистрирует пользователя
     *
     * @param array|string[] $formedData - Сформированный массив данных, общий для всех видов пользователей
     * @param array|string[] $notFormedData - Несформированный массив данных, индивидуальный для каждого
     * вида пользователей
     *
     * @return User|null
     */
    public function signUpUser(array $formedData, array $notFormedData): ?User
    {
        /** @var User|null $user - Зарегистрированный пользователь */
        $user = null;
        try {
            /** @var AddressType $addressType - Модель юридического типа адреса */
            $addressType = AddressType::cache(TTL::DAY)->filter(['UF_NAME' => 'Юридический'])->first();

            /** @var AddressModel $address - Модель созданного адреса */
            $address = AddressModel::create([
                'UF_COUNTRY' => $notFormedData['sign_up_country'],
                'UF_TYPE_ID' => $addressType->getId()
            ]);

            /** @var Bank $bankDetails - Модель созданных банковских реквизитов */
            $bankDetails = Bank::create([]);

            /** @var Company $company - Модель созданной компании */
            $company = Company::create([
                'UF_NAME' => ucfirst($notFormedData['sign_up_name']),
                'UF_ACTIVITY_ID' => $notFormedData['sign_up_company_activity'],
                'UF_EMAIL' => $notFormedData['sign_up_email'],
                'UF_PHONE' => $notFormedData['sign_up_phone'],
                'UF_TAX_NUMBER' => $notFormedData['sign_up_tax_number'],
                'UF_ADDRESS_ID' => $address->getId(),
                'UF_BANK_ID' => $bankDetails->getId(),
                'UF_TYPE_ID' => CompanyType::cache(TTL::DAY)
                    ->filter(['UF_NAME' => CompanyType::LEGAL_ENTITY])
                    ->first()
                    ->getId()
            ]);

            /** @var User $user - Модель пользователя */
            $user = User::create(array_merge($formedData, [
                'UF_COMPANY_ID' => $company->getId(),
                'UF_PURCHASE_UP_100' => false,
                'UF_PURCHASE_UP_600' => false,
                'UF_PURCHASE_OVER_600' => false,
                'UF_TAX_NUMBER' => $notFormedData['sign_up_tax_number'],
                'UF_CLIENT_PB' => $notFormedData['sign_up_clientPB'],
            ]));

            Contact::create(['UF_USER_ID' => $user->getId()]);

            if ($user && $company->address->country->getCrmId()) {
                $user->update(['UF_CRM_ID' => (new CrmLegalPerson())->setUser($user)->createPerson()]);
                $this->updateProfileFromCrm($user);
            }
        } catch (Throwable $exception) {
            logger(UserCore::LOGGER_NAME_AUTH_ERROR)
                ->error(
                    'Не удалось зарегистрировать пользователя ' .$notFormedData['sign_up_email']
                    . '. Причина: ' . $exception->getMessage()
                );
        } finally {
            return $user;
        }
    }

    /**
     * Получаем символьный код типа пользователя в БД
     *
     * @return string
     */
    public function getPersonTypeCode(): string
    {
        return self::PERSON_TYPE_CODE;
    }

    /**
     * Получает класс для связи с crm для юр лица
     *
     * @return CrmLegalPerson
     */
    public function getCrmClass(): CrmLegalPerson
    {
        return new CrmLegalPerson();
    }

    /**
     * Осуществляет проверку целостности полей
     *
     * @param User $user
     * @param array $formData
     *
     * @throws \App\Core\CRM\FieldsIntegrality\FieldIntegralityCheckerException
     */
    public function checkFieldsIntegrality(User $user, array $formData): void
    {
        (new AddressChecker())->setFields(
            ['company-address-country' => $formData['company-address-country']],
            ['company-address-zip-code' => $formData['company-address-zip-code']],
            ['company-address-region' => $formData['company-address-region']],
            ['company-address-city' => $formData['company-address-city']],
            ['company-address-street' => $formData['company-address-street']],
            ['company-address-house' => $formData['company-address-house']],
            ['company-address-apartment' => $formData['company-address-apartment']]
        )->setUser($user)->check();

        (new BankDetails())->setFields(
            ['company-bank-name' => $formData['company-bank-name']],
            ['company-bank-checking-account' => $formData['company-bank-checking-account']],
            ['company-bank-bik' => $formData['company-bank-bik']],
            ['company-bank-corr-account' => $formData['company-bank-corr-account']],
            ['company-legal-information-tax-number' => $formData['company-legal-information-tax-number']],
            ['company-bank-kpp' => $formData['company-bank-kpp']],
            ['company-bank-okpo' => $formData['company-bank-okpo']]
        )->check();
    }

    /**
     * Сохраняет в базу информацию по профилю
     *
     * @param User $user - Пользователь
     * @param array $formData - Данные из формы
     *
     * @return bool
     *
     * @throws \App\Core\CRM\FieldsIntegrality\FieldIntegralityCheckerException
     */
    public function setProfileData(User $user, array $formData): bool
    {
        $this->checkFieldsIntegrality($user, $formData);

        $result = true;
        try {
            $user->update([
                'UF_USER_NAME_RU' => $formData['company-representative-name'],
                'UF_USER_NAME_EN' => $formData['company-representative-name'],
                'UF_USER_NAME_CN' => $formData['company-representative-name'],
                'PERSONAL_PHONE' => $formData['company-representative-phone'],
                'EMAIL' => $formData['company-representative-email'],
                'WORK_POSITION' => $formData['company-representative-position']
            ]);

            $user->company->address->update([
                'UF_COUNTRY' => $formData['company-address-country'],
                'UF_REGION' => $formData['company-address-region'],
                'UF_ZIP' => $formData['company-address-zip-code'],
                'UF_CITY' => $formData['company-address-city'],
                'UF_STREET' => $formData['company-address-street'],
                'UF_HOUSE' => $formData['company-address-house'],
                'UF_FLAT' => $formData['company-address-apartment']
            ]);

            $user->company->bank->update([
                'UF_NAME' => $formData['company-bank-name'],
                'UF_BIK' => $formData['company-bank-bik'],
                'UF_COR_ACCOUNT' => $formData['company-bank-corr-account'],
                'UF_CHECK_ACCOUNT' => $formData['company-bank-checking-account'],
                'UF_TAX_ID' => $formData['company-bank-tax-number'],
                'UF_KPP' => $formData['company-bank-kpp'],
                'UF_OKPO' => $formData['company-bank-okpo']
            ]);

            $user->company->update([
                'UF_NAME' => $formData['company-name'],
                'UF_ACTIVITY_ID' => $formData['company-legal-information-activity'],
                'UF_EMAIL' => $formData['company-legal-information-email'],
                'UF_PHONE' => $formData['company-legal-information-phone'],
                'UF_TAX_NUMBER' => $formData['company-legal-information-tax-number']
            ]);

            $user->update([
                'EMAIL' => $formData['company-legal-information-email'],
                'LOGIN' => $formData['company-legal-information-email']
            ]);
        } catch (Throwable $exception) {
            $result = false;
            logger('api')
                ->error(self::class . ' Не удалось обновить профиль компании: ' . $exception->getMessage());
        } finally {
            return $result;
        }
    }

    /**
     * Обновляет данные пользователя в БД ИМ на основе данных из CRM
     *
     * @param User $user - Модель пользователя
     *
     * @return void
     *
     * @throws CrmUserNotFoundException
     * @throws \Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix
     */
    public function updateProfileFromCrm(User $user): void
    {
        $this->legalEntity = (new CrmLegalPerson())->getPerson($user->getCrmId());

        if ($this->legalEntity) {
            /** @var LegalPersonAddress $legalPersonAddress - Экземпляр класса для работы с адресами */
            $legalPersonAddress = (new LegalPersonAddress())->setUser($this->legalEntity);

            /** @var Address $legalAddress - Юридический адрес */
            $legalAddress = $legalPersonAddress->getGeneralAddress();

            /** @var array|Address[] $deliveryAddresses - Массив объектов, описывающих адреса доставки */
            $deliveryAddresses = $legalPersonAddress->getDeliveryAddresses();

            $user->update($this->generateUserData($legalAddress));
            $user->contact->update($this->generateContactData());
            $user->company->update($this->generateCompanyData($legalAddress));
            $user->company->address->update($this->generateCompanyAddressData($legalAddress));
            $user->company->bank->update($this->generateCompanyBankDetails());

            foreach ($user->deliveryAddresses as $deliveryAddress) {
                $deliveryAddress->delete();
            }
            foreach ($deliveryAddresses as $deliveryAddress) {
                AddressModel::create(
                    AddressHelper::generateDeliveryAddressDataFromCrmObject($deliveryAddress, $user)
                );
            }
        } else {
            throw new CrmUserNotFoundException($user, CrmLegalPerson::GET_PERSON);
        }
    }

    /**
     * Создает пользователей и все их данные в БД ИМ на основе данных из CRM
     *
     * @return void
     */
    public function createProfileFromCrm(): void
    {
        if (!$this->legalEntity) {
            return;
        }

        /** @var LegalPersonAddress $legalPersonAddress - Экземпляр класса для работы с адресами */
        $legalPersonAddress = (new LegalPersonAddress())->setUser($this->legalEntity);

        /** @var Address $legalAddress - Юридический адрес */
        $legalAddress = $legalPersonAddress->getGeneralAddress();

        /** @var array|Address[] $deliveryAddresses - Массив объектов, описывающих адреса доставки */
        $deliveryAddresses = $legalPersonAddress->getDeliveryAddresses();

        /** @var Bank $bank - Модель созданных банкосвких реквизитов */
        $bank = Bank::create($this->generateCompanyBankDetails());

        /** @var AddressModel $legalAddressModel - Модель созданного юридического адреса */
        $legalAddressModel = AddressModel::create($this->generateCompanyAddressData($legalAddress));

        /** @var Company $company - Компания */
        $company = Company::create(
            array_merge(
                $this->generateCompanyData($legalAddress),
                [
                    'UF_ADDRESS_ID' => $legalAddressModel->getId(),
                    'UF_BANK_ID' => $bank->getId()
                ]
            )
        );

        /** @var string $password - Пароль для нового пользователя */
        $password = generate_hash(8);

        /** @var User $user - Модель нового пользователя */
        $user = User::create(
            array_merge(
                $this->generateUserData($legalAddress),
                [
                    'UF_COMPANY_ID' => $company->getId(),
                    'PASSWORD' => $password,
                    'REPEAT_PASSWORD' => $password
                ]
            )
        );

        Contact::create(array_merge($this->generateContactData(), ['UF_USER_ID' => $user->getId()]));
        foreach ($deliveryAddresses as $deliveryAddress) {
            AddressModel::create(AddressHelper::generateDeliveryAddressDataFromCrmObject($deliveryAddress, $user));
        }

        $user->setNotHashedPassword($password);
        UserCore::sendEmailToImportedUser($user);
    }

    /**
     * Сохраняет данные анкеты
     *
     * @param User $user Модель текущего пользователя
     * @param array|mixed[] $formData Массив, описывающий данные из формы
     *
     * @return void
     *
     * @throws Exception
     */
    public function setProfileFormData(User $user, array $formData): void
    {
        if ($formData['entity_type'] == PersonalFormLegalEntity::getFormTypeCode() . '_entity'
            || $user->company->type->getName() == CompanyType::LEGAL_ENTITY) {
            (new PersonalFormLegalEntity($user, $formData))->processForm();
        } elseif ($formData['entity_type'] == PersonalFormLegalIndividual::getFormTypeCode() . '_entity'
            || $user->company->type->getName() == CompanyType::INDIVIDUAL_ENTITY) {
            (new PersonalFormLegalIndividual($user, $formData))->processForm();
        }
    }

    /**
     * Возвращает класс для работы с CRM в зависимости от типа лица
     *
     * @return CrmClientAbstract
     */
    public function getCrmPersonClass(): CrmClientAbstract
    {
        return new CrmLegalPerson();
    }
}

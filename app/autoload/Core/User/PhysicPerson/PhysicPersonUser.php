<?php

namespace App\Core\User\PhysicPerson;

use App\Api\External\CRM\CrmClientAbstract;
use App\Api\External\CRM\User\CrmPhysicPerson;
use App\Core\BitrixProperty\Property;
use App\Core\CRM\IdentityDocumentType;
use App\Core\CRM\PersonalForm\PhysicPerson\PersonalFormPhysicNonResident;
use App\Core\CRM\PersonalForm\PhysicPerson\PersonalFormPhysicResident;
use App\Core\Email;
use App\Core\User\Address\PhysicPersonAddress;
use App\Core\User\CrmUserNotFoundException;
use App\Core\User\General\Entity\Address;
use App\Core\User\PhysicPerson\Entity\PhysicEntity;
use App\Core\User\User as UserCore;
use App\Core\User\UserInterface;
use App\Helpers\AddressHelper;
use App\Helpers\GenderHelper;
use App\Models\Auxiliary\CRM\FamilyStatus;
use App\Models\Auxiliary\CRM\Gender;
use App\Models\HL\Address as AddressModel;
use App\Models\HL\AddressType;
use App\Models\HL\PassportData;
use App\Models\User;
use Bitrix\Main\Type\Date;
use CEvent;
use DateTime as DateTimePhp;
use Exception;
use Throwable;

/**
 * Класс для работы с физическим лицом
 * Class PhysicPersonUser
 * @package App\Core\User\PhysicPerson
 */
class PhysicPersonUser implements UserInterface
{
    /** @var string - Символьный код типа пользователя */
    public const PERSON_TYPE_CODE = 'PHYSICAL_ENTITY';

    /** @var PhysicEntity $physicEntity - Объект, описывающий физ лицо */
    private $physicEntity;

    /**
     * Генерирует массив, описывающий поля пользователя
     *
     * @param Address $address - Объект, описывающий адрес в CRM
     *
     * @return array
     */
    private function generateUserData(Address $address): array
    {
        /** @var Gender $gender - Пол пользователя */
        $gender = Gender::where('code', $this->physicEntity->getGender())->first();

        /** @var FamilyStatus $familyStatus - Семейное положение пользователя */
        $familyStatus = FamilyStatus::where('code', $this->physicEntity->getOtherData()->getFamilyStatus())
            ->first();

        return [
            'LOGIN' => $address->getEmail(),
            'EMAIL' => $address->getEmail(),
            'UF_IS_APPROVING' => false,
            'UF_CAN_AUCTION' => $this->physicEntity->isAuction(),
            'UF_PURCHASE_UP_100' => $this->physicEntity->isPurchaseUpTo100(),
            'UF_PURCHASE_UP_600' => $this->physicEntity->isPurchaseUpTo600(),
            'UF_PURCHASE_OVER_600' => $this->physicEntity->isPurchaseOver600(),
            'UF_COUNTRY' => $address->getCountry(),
            'UF_APPEAL' => GenderHelper::getAppealByGenderAndFamilyStatus($gender, $familyStatus),
            'UF_NAME_RU' => $this->physicEntity->getName(),
            'UF_NAME_EN' => $this->physicEntity->getName(),
            'UF_NAME_CN' => $this->physicEntity->getName(),
            'UF_MIDDLE_NAME' => $this->physicEntity->getMiddleName(),
            'USER_SURNAME_RU' => $this->physicEntity->getSurname(),
            'USER_SURNAME_EN' => $this->physicEntity->getSurname(),
            'USER_SURNAME_CN' => $this->physicEntity->getSurname(),
            'UF_USER_ENTITY_TYPE' => Property::getUserTypeListPropertyValue(
                'UF_USER_ENTITY_TYPE',
                self::PERSON_TYPE_CODE
            )->getVariantId(),
            'UF_CRM_ID' => $this->physicEntity->getCrmId()
        ];
    }

    /**
     * Генерирует массив, описывающий поля адреса
     *
     * @param Address $address - Объект, описывающий адрес в CRM
     *
     * @return array|mixed[]
     */
    private function generatePhysicAddressData(Address $address): array
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
     * Записывает в свойство объект, описывающий юр лицо
     *
     * @param PhysicEntity $physicEntity - Объект, описывающий юр лицо
     *
     * @return PhysicPersonUser
     */
    public function setUser(PhysicEntity $physicEntity): self
    {
        $this->physicEntity = $physicEntity;
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
            $user = User::create($formedData);
        } catch (Throwable $exception) {
            logger(UserCore::LOGGER_NAME_AUTH_ERROR)
                ->error(
                    'Не удалось зарегистрировать пользователя '.$notFormedData['signup_email']
                    .'. Причина: '.$exception->getMessage()
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
     * Получает класс для связи с crm для физ лица
     *
     * @return CrmPhysicPerson
     */
    public function getCrmClass(): CrmPhysicPerson
    {
        return new CrmPhysicPerson();
    }

    /**
     * Сохраняет в базу информацию по профилю
     *
     * @param User $user - Пользователь
     * @param array $formData - Данные из формы
     * @return bool
     */
    public function setProfileData(User $user, array $formData): bool
    {
        /** @var bool $result - Результат обновления пользователя */
        $result = true;

        try {
            $user->update([
                'UF_USER_NAME_RU' => $formData['contacts-name'],
                'UF_USER_NAME_EN' => $formData['contacts-name'],
                'UF_USER_NAME_CN' => $formData['contacts-name'],
                'UF_USER_SURNAME_RU' => $formData['contacts-surname'],
                'UF_USER_SURNAME_EN' => $formData['contacts-surname'],
                'UF_USER_SURNAME_CN' => $formData['contacts-surname'],
                'UF_USER_MIDDLE_NAME' => $formData['contacts-patronym'],
                'PERSONAL_PHONE' => $formData['contacts-phone'],
                'EMAIL' => $formData['contacts-email'],
                'UF_APPEAL' => GenderHelper::getSalutationByRegFormInfo($formData['contacts-sex'])->getId()
            ]);

            $user->passportData->update([
                'UF_BIRTHDAY' => Date::createFromPhp(
                    new DateTimePhp($formData['contacts-birthday'])
                )
            ]);

            $user->physicAddress->update([
                'UF_COUNTRY' => $formData['contacts-country']
            ]);
        } catch (Throwable $exception) {
            $result = false;
            logger(UserCore::LOGGER_NAME_PROFILE_ERROR)
                ->error(
                    'Не удалось обновить профиль пользователя '
                    . $user->getId() . '. Причина: ' . $exception->getMessage()
                );
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
        $this->physicEntity = (new CrmPhysicPerson())->getPerson($user->getCrmId());

        if ($this->physicEntity) {
            /** @var PhysicPersonAddress $physicPersonAddress - Экземпляр класса для работы с адресами */
            $physicPersonAddress = (new PhysicPersonAddress())->setUser($this->physicEntity);

            /** @var Address $homeAddress - Домашний адрес пользователя */
            $homeAddress = $physicPersonAddress->getGeneralAddress();

            /** @var array|Address[] $deliveryAddresses - Массив объектов, описывающих адреса доставки */
            $deliveryAddresses = $physicPersonAddress->getDeliveryAddresses();

            $oldPerson = clone $user;
            $user->update($this->generateUserData($homeAddress));
            $this->checkNewStatus($oldPerson, $user);
            $user->physicAddress->update($this->generatePhysicAddressData($homeAddress));
            foreach ($user->deliveryAddresses as $deliveryAddress) {
                $deliveryAddress->delete();
            }
            foreach ($deliveryAddresses as $deliveryAddress) {
                AddressModel::create(
                    AddressHelper::generateDeliveryAddressDataFromCrmObject($deliveryAddress, $user)
                );
            }

            $user->passportData->update([
                'UF_CRM_ID' => $this->physicEntity->getIdentityDocument()->getCrmId(),
                'UF_REG_COUNTRY' => $this->physicEntity->getIdentityDocument()->getRegCountry()
            ]);
        } else {
            throw new CrmUserNotFoundException($user, CrmPhysicPerson::GET_PERSON);
        }
    }

    /**
     * Проверяет был ли изменен статус пользователя
     *
     * @param PhysicPersonUser $oldUser
     * @param PhysicPersonUser $newUser
     */
    public function checkNewStatus(User $oldUser, User $newUser)
    {
        //Если стала доступна покупка выше 100к
        if (!$oldUser->isPurchaseAvailableOver100() && $newUser->isPurchaseAvailableOver100()) {
            Email::sendMail(
                'CRM_VERIFICATION_COMPLETED',
                [
                    'EMAIL_TO' => $newUser->getEmail(),
                    'user_id'  => $newUser->getId(),
                ],
                'N',
                $newUser);
        }
    }

    /**
     * Создает пользователей и все их данные в БД ИМ на основе данных из CRM
     *
     * @return void
     */
    public function createProfileFromCrm(): void
    {
        if (!$this->physicEntity) {
            return;
        }

        /** @var PhysicPersonAddress $physicPersonAddress - Экземпляр класса для работы с адресами */
        $physicPersonAddress = (new PhysicPersonAddress())->setUser($this->physicEntity);

        /** @var Address $legalAddress - Юридический адрес */
        $homeAddress = $physicPersonAddress->getGeneralAddress();

        /** @var array|Address[] $deliveryAddresses - Массив объектов, описывающих адреса доставки */
        $deliveryAddresses = $physicPersonAddress->getDeliveryAddresses();

        /** @var string $password - Пароль для нового пользователя */
        $password = generate_hash(8);

        /** @var User $user - Модель нового пользователя */
        $user = User::create(
            array_merge(
                $this->generateUserData($homeAddress),
                ['PASSWORD' => $password, 'REPEAT_PASSWORD' => $password]
            )
        );

        AddressModel::create(
            array_merge($this->generatePhysicAddressData($homeAddress), ['UF_USER_ID' => $user->getId()])
        );

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
        if ($formData['form_foreign_document_type']
            || $formData['form_foreign_document_num']
            || $formData['form_foreign_document_time']) {
            (new PersonalFormPhysicNonResident($user, $formData))->processForm();
        } else {
            (new PersonalFormPhysicResident($user, $formData))->processForm();
        }
    }

    /**
     * Возвращает класс для работы с CRM в зависимости от типа лица
     *
     * @return CrmClientAbstract
     */
    public function getCrmPersonClass(): CrmClientAbstract
    {
        return new CrmPhysicPerson();
    }
}

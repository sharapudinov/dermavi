<?php

namespace App\Core\User\PhysicPerson\Entity;

use App\Core\User\Subscriptions\Subscription;
use App\Models\Auxiliary\CRM\OtherContactType;
use App\Models\User;
use Exception;

/**
 * Класс для описания сущности "Контактная информация"
 * Class ContactData
 * @package App\Core\User\PhysicPerson\Entity
 */
class ContactData
{
    /** @var string|null $MobilePhone - Мобильный телефон */
    private $MobilePhone;

    /** @var string|null $Email - Email */
    private $Email;

    /** @var bool|null $DoNotUseEmail - Не использовать Email */
    private $DoNotUseEmail;

    /** @var bool|null $DoNotUseCall - Не использовать телефон */
    private $DoNotUseCall;

    /** @var bool|null $DoNotUseSms - Не использовать SMS */
    private $DoNotUseSms;

    /** @var bool|null $DoNotUseMail - Не использовать почту */
    private $DoNotUseMail;

    /** @var bool|null $IsAgreeToAdvertising - Согласие на получение рекламы */
    private $IsAgreeToAdvertising;

    /** @var BulkEmail $BulkEmail - Подписка на рассылки email */
    private $BulkEmail;

    /** @var Other|null $Other - Дополнительные средства связи */
    private $Other;

    /**
     * Заполняет объект данными из БД
     *
     * @param User $user Модель пользователя
     *
     * @return ContactData
     *
     * @throws Exception
     */
    public function fillFromDatabase(User $user): self
    {
        $this->MobilePhone = $user->getPhone();
        $this->Email = $user->getEmail();
        $this->IsAgreeToAdvertising = Subscription::isUserSubscribed($user->getEmail());
        $this->BulkEmail = (new Subscription())->getUserSubscriptionsList($user);

        /** @var OtherContactType $additionalPhoneType Модель дополнительного телефона */
        $additionalPhoneType = OtherContactType::getAdditionalPhone();

        /**
         * @var array|string[] $additionalPhoneTypeArray
         * Массив, содержащий количество символьных кодов доп телефона равное количеству доп телефонов пользователя
         */
        $additionalPhoneTypeArray = [];
        for ($i = 0; $i < count($user->getAdditionalPhones()); $i++) {
            $additionalPhoneTypeArray[] = $additionalPhoneType->getXmlId();
        }

        /** @var OtherContactType $additionalPhoneType Модель дополнительного email'a */
        $additionalEmailType = OtherContactType::getAdditionalEmail();

        /**
         * @var array|string[] $additionalEmailTypeArray
         * Массив, содержащий количество символьных кодов доп email'ов равное количеству доп email'ов пользователя
         */
        $additionalEmailTypeArray = [];
        for ($i = 0; $i < count($user->getAdditionalEmails()); $i++) {
            $additionalEmailTypeArray[] = $additionalEmailType->getXmlId();
        }

        //Объединяем доп телефоны и email'ы в один массив
        $this->Other = array_replace(
            array_combine($user->getAdditionalPhones(), $additionalPhoneTypeArray),
            array_combine($user->getAdditionalEmails(), $additionalEmailTypeArray)
        );

        return $this;
    }

    /**
     * Получаем мобильный телефон
     *
     * @return null|string
     */
    public function getMobilePhone(): ?string
    {
        return $this->MobilePhone;
    }

    /**
     * Получаем email
     *
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->Email;
    }

    /**
     * Не использовать email
     *
     * @return bool|null
     */
    public function doNotUseEmail(): ?bool
    {
        return $this->DoNotUseEmail;
    }

    /**
     * Не использовать телефон
     *
     * @return bool|null
     */
    public function doNotUseCall(): ?bool
    {
        return $this->DoNotUseCall;
    }

    /**
     * Не использовать смс
     *
     * @return bool|null
     */
    public function doNotUseSms(): ?bool
    {
        return $this->DoNotUseSms;
    }

    /**
     * Не использовать почту
     *
     * @return bool|null
     */
    public function doNotUseMail(): ?bool
    {
        return $this->DoNotUseMail;
    }

    /**
     * Согласен ли на получение рекламы
     *
     * @return bool|null
     */
    public function isAgreeToAdvertising(): ?bool
    {
        return $this->IsAgreeToAdvertising;
    }

    /**
     * Получаем подписку на рассылки email
     *
     * @return null|BulkEmail
     */
    public function getBulkEmail(): ?BulkEmail
    {
        return $this->BulkEmail;
    }

    /**
     * Получаем дополнительные средства связи
     *
     * @return Other|null
     */
    public function getOther(): ?Other
    {
        return $this->Other;
    }
}

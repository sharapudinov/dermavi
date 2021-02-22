<?php

namespace App\Models;

use App\Core\Sale\PersonType;
use App\Core\User\User as CoreUser;
use App\Helpers\LanguageHelper;
use App\Helpers\UserHelper;
use App\Models\Auxiliary\Subscriptions\Subscription;
use App\Models\HL\Address;
use App\Models\HL\AddressType;
use App\Models\HL\Company\Company;
use App\Models\HL\Contact;
use App\Models\HL\Country;
use App\Models\HL\DiamondOrder;
use App\Models\HL\Document;
use App\Models\HL\PassportData;
use App\Models\HL\PersonalForm;
use App\Models\HL\SalutationType;
use Arrilot\BitrixModels\Models\UserModel;
use Arrilot\BitrixModels\Queries\BaseQuery;
use CFile;
use CUserFieldEnum;
use Illuminate\Support\Collection;

/**
 * Класс-модель для сущности "Пользователь"
 *
 * Class User
 * @package App\Models
 *
 * @property-read Collection|Address[]  $deliveryAddresses
 * @property-read Company               $company
 * @property-read Country               $country
 * @property-read Contact               $contact
 * @property-read Address               $physicAddress
 * @property-read PassportData          $passportData
 * @property-read User                  $manager
 * @property-read PersonalForm          $personalInfoForm
 * @property-read Address               $registrationAddress
 * @property-read Collection|Document[] $documents
 * @property-read Subscription          $subscription
 * @property-read SalutationType        $salutation
 */
class User extends UserModel
{
    /** @var string|null $notHashedPassword - Не захешированный пароль */
    private $notHashedPassword;

    /**
     * Авторизуем пользователя по идентификатору
     *
     * @param int $userId - идентификатор пользователя
     *
     * @return void
     */
    public function authUser(int $userId = null): void
    {
        global $USER;
        $USER->Authorize($userId ?? $this['ID']);
    }

    /**
     * Получаем идентикатор пользователя
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)$this['ID'];
    }

    /**
     * Получаем email пользователя
     *
     * @return string
     */
    public function getEmail(): string
    {
        return (string)$this['EMAIL'];
    }

    /**
     * @return string
     */
    public function getLid(): string
    {
        return $this['LID'];
    }

    /**
     * Возвращает массив дополнительных email'ов
     *
     * @return array|string[]
     */
    public function getAdditionalEmails(): array
    {
        return (array)$this['UF_ADD_EMAILS'];
    }

    /**
     * Получает имя пользователя
     *
     * @param string|null $language Язык, на котором необходимо получить имя
     *
     * @return null|string
     */
    public function getName(string $language = null): ?string
    {
        return LanguageHelper::getUserMultilingualFieldValue($this, 'USER_NAME', $language);
    }

    /**
     * Получает фамилию
     *
     * @param string|null $language Язык, на котором необходимо получить имя
     *
     * @return null|string
     */
    public function getSurname(string $language = null): ?string
    {
        return LanguageHelper::getUserMultilingualFieldValue($this, 'USER_SURNAME', $language);
    }

    /**
     * Получает отчество
     *
     * @return null|string
     */
    public function getMiddleName(): ?string
    {
        return $this['UF_USER_MIDDLE_NAME'];
    }

    /**
     * Получает полное имя пользователя
     *
     * @param string|null $language Язык, на котором необходимо получить имя
     *
     * @return null|string
     */
    public function getFullName(string $language = null): ?string
    {
        return $this->getName($language) . ' ' . $this->getSurname($language);
    }

    /**
     * Получает полное имя пользователя c отчеством
     *
     * @return null|string
     */
    public function getFIO(): ?string
    {
        return $this->getSurname()
            . ' ' . $this->getName()
            . ($this->getMiddleName() ? ' ' . $this->getMiddleName() : '');
    }

    /**
     * Получаем тип лица пользователя (юридическое/физическое)
     *
     * @return string
     */
    public function getUserEntityType(): array
    {
        $userFieldEnum = new CUserFieldEnum();

        return $userFieldEnum->GetList([], ['ID' => $this['UF_USER_ENTITY_TYPE']])->Fetch();
    }

    /**
     * Получаем символьный код типа плательщика, которому принадлежит пользователь
     *
     * @return string
     */
    public function getUserEntityTypeCode(): string
    {
        return $this->getUserEntityType()['XML_ID'];
    }

    /**
     * Является ли пользователь юридическим лицом
     *
     * @return bool
     */
    public function isLegalEntity(): bool
    {
        return $this->getUserEntityTypeCode() == PersonType::LEGAL_ENTITY;
    }

    /**
     * Является ли пользователь физическим лицом
     *
     * @return bool
     */
    public function isNaturalPersonEntity(): bool
    {
        return $this->getUserEntityTypeCode() == PersonType::PHYSICAL_ENTITY;
    }

    /**
     * Возвращает истину, если пользователь входит хотя бы в одну из групп. Группы задаются в виде кодов,
     * а не идентификаторов.
     *
     * @param string|array $groupCodes
     * @param bool         $adminInAll - если истина, то наличие прав администратора, подразумевает и вхождение
     *                                 в любую из заданных групп,
     *
     * @return bool
     */
    public function isInGroup($groupCodes, bool $adminInAll = true): bool
    {
        if (!$this->isAuthorized()) {
            return false;
        }

        if ($adminInAll && $this->isAdmin()) {
            return true;
        }

        $groupIds = array_values(UserHelper::getUserGroupsIds((array)$groupCodes));

        return !empty(array_intersect($this->getGroups(), $groupIds));
    }

    /**
     * Получаем хэш пользователя
     *
     * @return null|string
     */
    public function getHash(): ?string
    {
        if (!$this['UF_HASH']) {
            CoreUser::setUserHash($this);
        }

        return $this['UF_HASH'];
    }

    /**
     * Получаем crm id пользователя
     *
     * @return null|string
     */
    public function getCrmId(): ?string
    {
        return $this['UF_CRM_ID'];
    }

    /**
     * Сохраняет в модели персональное фото
     *
     * @return void
     */
    public function setPhoto(): void
    {
        $this->photo = CFile::GetPath($this['PERSONAL_PHOTO']);
    }

    /**
     * Получает персональное фото
     *
     * @return null|string
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * Получает телефон пользователя
     *
     * @return string|null
     *
     */
    public function getPhone(): string
    {
        return $this['PERSONAL_PHONE'] ?? $this->company->getPhone();
    }

    /**
     * Возвращает телефон пользователя без первой цифры (7/8) для формы с маской +7
     *
     * @return string|null
     */
    public function getPhoneForMaskForm(): ?string
    {
        // ALRSUP-2012 Не обрезаем два символа, а делаем замену "+", т.к. есть возможность сохранить номер без "+"
        return substr(str_replace('+', '', $this->getPhone()), 1);
    }

    /**
     * Возвращает массив дополнительных телефонов
     *
     * @return array|string[]
     */
    public function getAdditionalPhones(): array
    {
        return (array)$this['UF_ADD_PHONES'];
    }

    /**
     * Проверяет, является ли пользователь менеджером аукционом
     *
     * @return bool
     */
    public function isAuctionManager(): bool
    {
        return $this->isInGroup('auctions_manager');
    }

    /**
     * Получает идентификатор страны пользователя
     *
     * @return int
     */
    public function getCountry(): int
    {
        return (int)$this['UF_COUNTRY'];
    }

    /**
     * Получает обращение к пользователю (mr, miss, mrs)
     *
     * @return string|null
     */
    public function getAppeal(): ?string
    {
        return $this['UF_APPEAL'];
    }

    /**
     * Получает хешированный пароль пользователя
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this['PASSWORD'];
    }

    /**
     * Записывает в модель незахешированный пароль
     *
     * @param string $password
     */
    public function setNotHashedPassword(string $password): void
    {
        $this->notHashedPassword = $password;
    }

    /**
     * Получает незахешированный пароль
     *
     * @return string|null
     */
    public function getNotHashedPassword(): ?string
    {
        return $this->notHashedPassword;
    }

    /**
     * Получает должность пользователя в компании
     *
     * @return string|null
     */
    public function getCompanyPosition(): ?string
    {
        return $this['WORK_POSITION'];
    }

    /**
     * Возвращает доступную сумму для покупки для текущего пользователя
     *
     * @return float
     */
    public function getAvailablePurchaseSumInCurrentCurrency(): float
    {
        return UserHelper::getUserAvailablePurchaseSum($this);
    }

    /**
     * Возвращает флаг того, что заявка пользователя на редактирование профиля все еще в процессе одобрения СБ
     *
     * @return bool
     */
    public function isApprovingInProgress(): bool
    {
        return $this['UF_IS_APPROVING'] ?? false;
    }

    /**
     * Возвращает дату рождения пользователя
     *
     * @param string|null $format - Формат, в котором нужно вывести дату
     *
     * @return string
     */
    public function getBirthday(string $format = null): ?string
    {
        $birthday = $this['PERSONAL_BIRTHDAY'];
        if ($birthday && !is_null($format)) {
            $birthday = ConvertDateTime($birthday, $format, true);
        }

        return $birthday;
    }

    /**
     * Возвращает ИНН
     *
     * @return string|null
     */
    public function getTaxNumber(): ?string
    {
        return $this['UF_TAX_NUMBER'];
    }

    /**
     * Возвращает место рждения
     *
     * @return string|null
     */
    public function getBirthPlace(): ?string
    {
        return $this['UF_PLACE_OF_BIRTH'];
    }

    /**
     * Возвращает флаг того, что пользователь является резидентом России
     *
     * @return bool
     */
    public function isResident(): bool
    {
        return $this->isLegalEntity()
            ? $this->company->address->country->isRussia()
            : $this->passportData->registerCountry->isRussia();
    }

    /**
     * Ссылка на ЛК пользователя для редактирования персональных данных
     *
     * @return string
     */
    public function getLkDataLink(): string
    {

        if ($this->isLegalEntity()) {
            $str = 'company-data';
        } else {
            $str = 'user-data';
        }

        return get_language_version_href_prefix() . '/personal/' . $str . '/';
    }

    /**
     * Получаем компанию, привязанну к пользователю
     *
     * @return BaseQuery
     */
    public function company(): BaseQuery
    {
        return $this->hasOne(Company::class, 'ID', 'UF_COMPANY_ID');
    }

    /**
     * Возвращает название компании
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->company instanceof Company
            ? (string)$this->company->getName()
            : '';
    }

    /**
     * Получаем менеджера, привязанного к пользователю
     *
     * @return BaseQuery
     */
    public function manager(): BaseQuery
    {
        return $this->hasOne(User::class, 'ID', 'UF_PERSONAL_MANAGER');
    }

    /**
     * Возвращает модель домашнего адреса пользователя
     *
     * @return BaseQuery
     */
    public function physicAddress(): BaseQuery
    {
        return $this->hasOne(Address::class, 'UF_USER_ID', 'ID')
                    ->filter(['UF_TYPE_ID' => AddressType::getPhysicAddressType()->getId()]);
    }

    /**
     * Получаем адреса доставок пользователя
     *
     * @return BaseQuery
     */
    public function deliveryAddresses(): BaseQuery
    {
        return $this->hasMany(Address::class, 'UF_USER_ID', 'ID')
                    ->filter(['UF_TYPE_ID' => AddressType::getDeliveryAddressType()->getId()]);
    }

    /**
     * Получает адрес регистрации пользователя
     *
     * @return BaseQuery
     */
    public function registrationAddress(): BaseQuery
    {
        return $this->hasOne(Address::class, 'UF_USER_ID', 'ID')
                    ->filter(['UF_TYPE_ID' => AddressType::getRegisterAddressType()->getId()]);
    }

    /**
     * Получаем документы пользователя
     *
     * @return BaseQuery
     */
    public function documents(): BaseQuery
    {
        return $this->hasMany(Document::class, 'UF_USER_ID', 'ID');
    }

    /**
     * Получает бриллианты под заказ, запрошенные пользователем
     *
     * @return BaseQuery
     */
    public function diamondOrders(): BaseQuery
    {
        return $this->hasMany(DiamondOrder::class, 'UF_USER_ID', 'ID');
    }

    /**
     * Получает страну пользователя
     *
     * @return BaseQuery
     */
    public function country(): BaseQuery
    {
        return $this->hasOne(Country::class, 'ID', 'UF_COUNTRY');
    }

    /**
     * Получает паспортные данные пользователя
     *
     * @return BaseQuery
     */
    public function passportData(): BaseQuery
    {
        return $this->hasOne(PassportData::class, 'ID', 'UF_PASSPORT_ID');
    }

    /**
     * Возвращает модель "Контакт"
     *
     * @return BaseQuery
     */
    public function contact(): BaseQuery
    {
        return $this->hasOne(Contact::class, 'UF_USER_ID', 'ID');
    }

    /**
     * Возвращает модель анкеты клиента
     *
     * @return BaseQuery
     */
    public function personalInfoForm(): BaseQuery
    {
        return $this->hasOne(PersonalForm::class, 'UF_USER_ID', 'ID');
    }

    /**
     * Возвращает запрос для получения модели подписки
     *
     * @return BaseQuery
     */
    public function subscription(): BaseQuery
    {
        return $this->hasOne(Subscription::class, 'USER_ID', 'ID');
    }

    /**
     * Возвращает запрос для получения модели типа обращения
     *
     * @return BaseQuery
     */
    public function salutation(): BaseQuery
    {
        return $this->hasOne(SalutationType::class, 'ID', 'UF_APPEAL');
    }

    /**
     * @return bool
     */
    public function isClientPB()
    {
        return (bool)$this['UF_CLIENT_PB'];
    }
}

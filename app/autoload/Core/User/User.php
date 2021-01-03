<?php

namespace App\Core\User;

use App\Core\BitrixEvent\EventMessage;
use App\Core\User\LegalPerson\LegalPersonUser;
use App\Core\User\PhysicPerson\PhysicPersonUser;
use App\Core\User\Subscriptions\Subscription;
use App\Core\User\Subscriptions\UserSubscription;
use App\Helpers\LanguageHelper;
use App\Helpers\SiteHelper;
use App\Helpers\UserHelper;
use App\Models\User as UserModel;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;
use CEvent;
use CUserTypeEntity;
use CUserFieldEnum;

/**
 * Класс для работы с пользователем
 * Class User
 * @package App\Core\User
 */
final class User
{
    /** @var string - Наименование логгера для записи информации, связанной с аутентификацией пользователя */
    public const LOGGER_NAME_AUTH_SUCCESS = 'internal_user_auth_info';

    /** @var string - Наименование логгера для записи ошибок, связанных с аутентификацией пользователя */
    public const LOGGER_NAME_AUTH_ERROR = 'internal_user_auth_error';

    /** @var string - Наименование логгера для записи информации, связанной с изменениями профиля пользователя */
    public const LOGGER_NAME_PROFILE_SUCCESS = 'internal_user_profile_info';

    /** @var string - Наименование логгера для записи ошибок, связанных с изменениями профиля пользователя */
    public const LOGGER_NAME_PROFILE_ERROR = 'internal_user_profile_error';

    /** @var string - Init dir для кеширования профилей пользователей */
    public const PERSONAL_INFO_PROFILE_CACHE_INIT_DIR = 'user_personal_info_profile_';

    /** @var null|UserModel $user - Пользователь */
    private $user;

    /** @var UserInterface $personType */
    private $personType;

    /** @var array $personTypePhysical - Возможные варианты для опредения физического лица */
    private $personTypePhysical;

    /** @var array $personTypeLegal - Возможные варианты для опредения юридического лица */
    private $personTypeLegal;

    /** @var mixed $crmInfo - Данные пользователя из crm */
    public $crmInfo;

    /** @var bool Открыт ли доступ к разделу "Распродажа ювелирных изделий" */
    private $jewelrySaleAllowed;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->personTypePhysical = [
            'Физическое лицо',
            'PHYSICAL_ENTITY'
        ];

        $this->personTypeLegal = [
            'Юридическое лицо',
            'LEGAL_ENTITY'
        ];
    }

    /**
     * Авторизуем пользователя по идентификатору
     *
     * @param int $userId - идентификатор пользователя
     * @return void
     */
    public static function authUserById(int $userId): void
    {
        global $USER;
        $USER->Authorize($userId);
    }

    /**
     * Авторизуем пользователя по логину и паролю
     *
     * @param string $login - Логин
     * @param string $password - Пароль
     * @return bool
     */
    public static function signIn(string $login, string $password): bool
    {
        global $USER;
        return $USER->Login($login, $password, 'Y', 'Y') === true;
    }

    /**
     * Авторизует пользователя по id
     *
     * @param int $userId - Идентификатор пользователя
     *
     * @return bool
     */
    public static function authById(int $userId): bool
    {
        global $USER;
        return $USER->Authorize($userId);
    }

    /**
     * Записываем пользователю уникальный хэш
     *
     * @param UserModel|null $user - Пользователь
     * @return void
     */
    public static function setUserHash(UserModel &$user = null): void
    {
        if (!$user) {
            $user = user();
        }

        if ($user) {
            $hashSetted = false;
            while ($hashSetted == false) {
                /** @var string $hash - Сгенерированный хэш */
                $hash = generate_hash(30);
                /** @var \Illuminate\Support\Collection $users - Коллекция пользователей */
                $users = UserModel::filter(['UF_HASH' => $hash])->getList();
                if ($users->isEmpty()) {
                    $user->update(['UF_HASH' => $hash]);
                    $hashSetted = true;
                }
            }
        }
    }

    /**
     * Отправляет письмо импортированному из crm пользователю
     *
     * @param UserModel $user - Модель нового пользователя
     *
     * @return void
     */
    public static function sendEmailToImportedUser(UserModel $user): void
    {
        if (!in_production()) {
            return;
        }

        /** @var string $userLanguage - Язык пользователя */
        $userLanguage = UserHelper::getUserLanguage($user);
        CEvent::SendImmediate('IMPORT_USER_FROM_CRM', SiteHelper::getSiteIdByLanguageId($userLanguage), [
            'EMAIL_TO' => $user->getEmail(),
            'PASSWORD' => $user->getNotHashedPassword(),
            'SITE_URL' => get_external_url(true, false)
        ], 'Y', '', [], $userLanguage);
    }

    /**
     * Определяем пользователя и тип его лица
     *
     * @param UserModel $user
     * @return User
     */
    public function setUserAndDefineUserPersonType(UserModel $user): self
    {
        $this->user = $user;
        $this->defineUserPersonType($user->getUserEntityTypeCode());
        return $this;
    }

    /**
     * Определяем тип лица пользователя
     *
     * @param string $personType
     * @return User
     */
    public function defineUserPersonType(string $personType): self
    {
        if (in_array($personType, $this->personTypePhysical)) {
            $this->personType = new PhysicPersonUser;
        } elseif (in_array($personType, $this->personTypeLegal)) {
            $this->personType = new LegalPersonUser;
        }

        return $this;
    }

    /**
     * Регистрирует пользователя
     *
     * @param array $signUpData - Массив данных из формы
     * @return bool
     * @throws \Exception
     */
    public function signUp(array $signUpData): bool
    {
        /** @var bool $success - Успешно ли зарегистрирован пользователь */
        $success = true;

        /** @var array $userData - Массив даных, общий для всех видов пользователей */
        $userData = [
            'LOGIN' => $signUpData['sign_up_email'],
            'EMAIL' => $signUpData['sign_up_email'],
            'PASSWORD' => $signUpData['sign_up_password'],
            'UF_COUNTRY' => $signUpData['sign_up_country'],
            'PERSONAL_PHONE' => $signUpData['sign_up_phone']
        ];

        /** @var array $entity - Поле "Тип пользователя" */
        $entity = CUserTypeEntity::GetList([], ['ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_USER_ENTITY_TYPE'])->Fetch();
        $userData['UF_USER_ENTITY_TYPE'] = CUserFieldEnum::GetList([], [
            'USER_FIELD_ID' => $entity['ID'], 'XML_ID' => $this->personType->getPersonTypeCode()
        ])->Fetch()['ID'];

        $signUpData['sign_up_clientPB'] = $this->isClientPB($signUpData['sign_up_clientPB']);

        if ($user = $this->personType->signUpUser($userData, $signUpData)) {
            static::authUserById($user->getId());

            $userLanguageInfo = $user->country
                ? $user->country->getCountryLanguageInfo()
                : [
                    'site_id'     => 's1',
                    'language_id' => 'en'
                ];

            LanguageHelper::setLanguageUserId($userLanguageInfo['language_id']);

            /** @var \App\Core\BitrixEvent\Entity\EventMessage $eventMessage - Почтовое событие */
            $eventMessage = EventMessage::getEventMessagesByCode(
                'SIGN_UP_USER',
                LanguageHelper::getLanguageVersion()
            )->first();
            CEvent::SendImmediate($eventMessage->getEventName(), SiteHelper::getSiteIdByCurrentLanguage(), [
                'EMAIL' => $signUpData['sign_up_email'],
                'PASSWORD' => $signUpData['sign_up_password'],
            ], 'Y', $eventMessage->getMessageId(), [], LanguageHelper::getLanguageVersion());

            if ($signUpData['auctionId']) {
                try {


                    CEvent::SendImmediate(
                        'AUCTION_USER_SIGNUP',
                        $userLanguageInfo['site_id'],
                        [
                            'USER_ID'  => $user->getId(),
                            'EMAIL_TO' => $user->getEmail(),
                            'ACTION_ID' => (int)$signUpData['auctionId'],
                        ],
                        'Y',
                        '',
                        [],
                        $userLanguageInfo['language_id']
                    );
                } catch (\Throwable $e) {
                    logger('common')->error($e->getMessage(), $e->getTrace());
                }
            }

            /** @var Subscription $subscriptionObject Экземпляр класса для работы с подпиской пользователя */
            $subscriptionObject = new Subscription();
            $subscription = $subscriptionObject->get(['EMAIL' => $user->getEmail()]);

            if ($subscription) {
                if ($signUpData['sign_up_subscription']) {
                    (new UserSubscription())
                        ->setUser($user)
                        ->setAllTypes()
                        ->edit($subscription['ID'], false);
                } else {
                    (new UserSubscription())
                        ->setUser($user)
                        ->setTypes($subscriptionObject->getTypes())
                        ->edit($subscription['ID'], false);
                }
            } elseif ($signUpData['sign_up_subscription']) {
                (new UserSubscription())->setUser($user)->setAllTypes()->add(false);
            } else {
                (new UserSubscription())->setUser($user)->add(false);
            }
        } else {
            $success = false;
        }

        return $success;
    }

    /**
     * Получает тип пользователя
     *
     * @return UserInterface
     */
    public function getPersonType(): UserInterface
    {
        return $this->personType;
    }

    /**
     * @param $sign_up_clientPB
     * @return bool
     */
    private function isClientPB($sign_up_clientPB)
    {
        return $sign_up_clientPB == 'Y';
    }

    /**
     * @param UserModel $user
     * @return static
     */
    public static function create(UserModel $user)
    {
        $instance = new static();
        $instance->setUserAndDefineUserPersonType($user);

        return $instance;
    }

    /**
     * @return UserModel|null
     */
    public function getUserModel(): ?UserModel
    {
        return $this->user;
    }

    /**
     * Имеет ли пользователь доступ к разделу распродажи ювелирных изделий
     *
     * @return bool
     */
    public function hasJewelrySaleAccess(): bool
    {
        if ($this->jewelrySaleAllowed === null) {
            $this->jewelrySaleAllowed = false;

            $userEmail = '';
            if ($this->user) {
                // Админам всегда разрешаем
                if ($this->user->isAdmin()) {
                    $this->jewelrySaleAllowed = true;
                } elseif (!$this->user->isLegalEntity()) {
                    // ALRSUP-1873: теперь пускаем всех авторизованных физиков
                    $this->jewelrySaleAllowed = true;
                    //$userEmail = trim($this->user->getEmail());
                }
            }

            if (!$this->jewelrySaleAllowed && $userEmail !== '') {
                try {
                    Loader::includeModule('iblock');
                    $item = ElementTable::getList(
                        [
                            'filter' => [
                                '=IBLOCK.CODE' => 'jewelry_sale_access',
                                '=IBLOCK.IBLOCK_TYPE_ID' => 'client',
                                '=ACTIVE' => 'Y',
                                '=CODE' => $userEmail,
                            ],
                            'select' => [
                                'ID',
                            ],
                            // Изменение в инфоблоке не сбрасывает кеш автоматически
                            /*
                            'cache' => [
                                'ttl' => 3600,
                                'cache_joins' => true,
                            ],
                            */
                            'limit' => 1,
                        ]
                    )->fetch();
                } catch (\Exception $exception) {
                    $item = null;
                }
                if ($item) {
                    $this->jewelrySaleAllowed = true;
                }
            }
        }

        return $this->jewelrySaleAllowed;
    }
}

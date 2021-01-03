<?php

namespace App\Helpers;

use App\Core\BitrixProperty\Property;
use App\Core\Currency\Currency;
use App\Models\User;
use Bitrix\Main\Context;

/**
 * Класс хелпер для работы с пользователями
 * Class User
 * @package App\Helpers
 */
class UserHelper
{
    /** @var string - Название для куки проверки, что пользователь не впервые на сайте */
    public const NOT_FIRST_VISIT_COOKIE = 'not_first_visit';

    /** @var string - Название для куки проверки, что пользователь прочитал попап с важной информацией */
    public const POPUP_IMPORTANT_INFO_READ = 'info-popup-read';

    /** @var array|null Зарегистрированные группы пользователей */
    private static $userGroups;

    /**
     * @var null|bool
     */
    private static $isNotAuthorizedLegalEntity;

    /**
     * Проверяем существует ли пользователь с указанным логином. Если нет, то регистрируем
     *
     * @param string $login - Логин
     * @return User - Найденный или зарегистрированный пользователь
     */
    public static function checkUserExistOrRegister(string $login): User
    {
        $user = User::getByLogin($login);
        $password = generate_hash(8);
        if (!$user) {
            $user = User::create([
                'LOGIN' => $login,
                'EMAIL' => $login,
                'PASSWORD' => $password,
                'REPEAT_PASSWORD' => $password,
                'UF_USER_ENTITY_TYPE' => Property::getUserTypeListPropertyValue('UF_USER_ENTITY_TYPE')
                    ->getPropertyId()
            ]);
        }

        return $user;
    }

    /**
     * Проверяем принял ли пользователь пользовательское соглашение (для предупреждения об использовании кук)
     *
     * @return bool
     */
    public static function acceptedPrivacyPolicy(): bool
    {
        return $_COOKIE['accepted_privacy_policy'] ?? false;
    }

    /**
     * Возвращает зарегистрированные группы пользователей.
     * @return array
     */
    public static function getUserGroupList(): array
    {
        if (self::$userGroups !== null) {
            return self::$userGroups;
        }

        self::$userGroups = cache('app_user_groups', TTL::DAY, function () {
            $result = [];
            $rs = \CGroup::GetList($by = 'c_sort', $order = 'desc');
            while ($row = $rs->Fetch()) {
                $result[$row['ID']] = $row;
            }
            return $result;
        });
        return self::$userGroups;
    }

    /**
     * Возвращает идентификатор группы пользователей по ее символьному коду.
     * @param string $code
     * @return int
     */
    public static function getUserGroupId(string $code): int
    {
        return (int) self::getUserGroupsIds([$code])[$code];
    }

    /**
     * Возвращает соответствие заданных кодов групп пользователей с их идентификаторами.
     * @param array $codes
     * @return array
     */
    public static function getUserGroupsIds(array $codes): array
    {
        $result = [];
        $codes = array_flip(array_unique($codes));

        foreach (self::getUserGroupList() as $group) {
            if (array_key_exists($group['STRING_ID'], $codes)) {
                $result[$group['STRING_ID']] = $group['ID'];
            }
        }

        return $result;
    }

    /**
     * Проверяет, что пользователь авторизован
     *
     * @return bool
     */
    public static function isAuthorized(): bool
    {
        global $USER;
        return $USER->isAuthorized();
    }

    /**
     * Получает язык пользователя в зависимости от его страны
     *
     * @param User $user - Пользователь
     * @return string
     */
    public static function getUserLanguage(User $user): string
    {
        if ($user->isLegalEntity()) {
            $country = $user->company->country;
        } else {
            $country = $user->country;
        }

        /** @var string $language - Язык пользователя */
        $language = 'en';
        if ($country && $country->isRussia()) {
            $language = 'ru';
        } elseif ($country && $country->isChina()) {
            $language = 'cn';
        }

        return $language;
    }

    /**
     * Возвращает флаг того, что пользователь является юр лицом
     *
     * @return bool
     */
    public static function isLegalEntity(): bool
    {
        // Для авторизованных пользователей возвращаем флаг из профиля
        if ($user = user()) {
            return $user->isLegalEntity();
        }

        if (static::isLegalEntityRequireAuth()) {
            return true;
        }

        return false;
    }

    /**
     * Определяет нужно ли показать пользователю страницу b2b с выводом автризации при любом действии пользователя
     *
     * Это возможно только на определённых страницах: главная и каталог камней.
     *
     * @return bool
     */
    public static function isLegalEntityRequireAuth(): bool
    {
        if (static::$isNotAuthorizedLegalEntity === null) {
            static::$isNotAuthorizedLegalEntity = !user()
                && !is_api()
                && !is_ajax()
                && !in_console()
                && Context::getCurrent()->getRequest()->get('variant') === 'b2b-require-auth'
                && is_directory('/diamonds/') // в разделе diamonds
                && Context::getCurrent()->getRequest()->getRequestedPageDirectory()
                !== sprintf('%s/diamonds', get_language_version_href_prefix()); // но не на главной странице раздела
        }

        return static::$isNotAuthorizedLegalEntity;
    }

    /**
     * Получает расшифрованный пароль пользователя
     *
     * @param string $password - Пароль, который надо преобразовать и сравнить
     * @param User $user - Пользователь
     * @return bool
     */
    public static function compareHashedPasswords(string $password, User $user): bool
    {
        $salt = substr($user->getPassword(), 0, (strlen($user->getPassword()) - 32));
        $realPassword = substr($user->getPassword(), -32);

        return $realPassword == md5($salt . $password);
    }

    /**
     * Возвращает сумму, на которую пользователь может совершить покупку
     *
     * @param User $user - Модель пользователя
     *
     * @return float
     */
    public static function getUserAvailablePurchaseSum(User $user): float
    {
        $sum = 0;
        if ($user->isPurchaseAvailableOver100()) {
            $sum = INF;
        } elseif ($user->isPurchaseAvailableUpTo100()) {
            $sum = round(PriceHelper::getPriceInSpecificCurrency(
                100000.0,
                Currency::getCurrentCurrency()
            ), -2);
        }

        return $sum;
    }

    /**
     * Возвращает массив цен, которые выводятся в статусе
     *
     * @return array|string[]
     */
    public static function getUserStatusPrices(): array
    {
        /** @var array|string[] $prices - Массив цен, которые выводятся в статусе */
        $prices = [100000];
        foreach ($prices as $key => $price) {
            $prices[$key] = str_replace(' ', '&nbsp;', NumberHelper::transformNumberToPrice(
                round(PriceHelper::getPriceInSpecificCurrency($price, Currency::getCurrentCurrency()), -2)
            ));
        }

        return $prices;
    }

    /**
     * Возратит true, если пользователь впервые на сайте и сайт открыт для России (RU)
     * @return bool
     */
    public static function isUserFirstVisit()
    {
        if (!$_COOKIE[self::NOT_FIRST_VISIT_COOKIE]) {
            setcookie(self::NOT_FIRST_VISIT_COOKIE, true, time() + TTL::YEAR, '/');
            if(LanguageHelper::getLanguageVersion() === 'ru'){
                return true;
            }
        }
        return false;
    }

    /**
     * Возратит true, если пользователь b2c зашел на русскую версию сайта и еще не читал важное сообщение в попапе
     * @return bool
     */
    public static function isPopupImportantInfoRead()
    {
        if (!$_COOKIE[self::POPUP_IMPORTANT_INFO_READ]) {
            if(LanguageHelper::getLanguageVersion() === 'ru') {
                if (!user() || !user()->isLegalEntity()) {
                    return false;
                }
            }
        }
        return true;
    }
}

<?php

namespace App\Core\SiteVersion;

use App\Helpers\LanguageHelper;
use App\Helpers\TTL;
use App\Models\HL\Country;
use App\Models\User;

/**
 * Класс, реализующий логику определения языковой версии сайта для авторизованного пользователя
 * Class AuthorizedUser
 *
 * @package App\Core\SiteVersion
 */
class AuthorizedUser implements SiteVersionInterface
{
    /** @var int $userId - Идентификатор пользователя */
    private $userId;

    /**
     * Записывает в свойство класса идентификатор пользователя
     *
     * @param int $userId - Идентификатор пользователя
     *
     * @return AuthorizedUser
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Возвращает идентификатор языковой версии сайта для текущего пользователя
     *
     * @return null|string
     */
    public function getUserSiteVersion(): ?string
    {
        return cache(
            get_default_cache_key(self::class) . '_user_' . $this->userId,
            TTL::DAY,
            function () {
                $user = User::cache(TTL::DAY)->filter(['ID' => $this->userId])->with('country')->first();
                if (!$user->country instanceof Country) {
                    return null;
                }
                $languageVersionInfo = LanguageHelper::getCountryLanguageAndSiteId($user->country);
                return $languageVersionInfo['language_id'] ?? null;
            }
        );
    }
}

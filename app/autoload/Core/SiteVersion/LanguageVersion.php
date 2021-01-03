<?php

namespace App\Core\SiteVersion;

use App\Helpers\LanguageHelper;

/**
 * Класс, реализующий логику определения языковой версии сайта для пользователя
 * Class LanguageVersion
 *
 * @package App\Core\SiteVersion
 */
class LanguageVersion
{
    /**
     * Возвращает идентификатор языковой версии сайта для текущего пользователя
     *
     * @return null|string
     */
    public function getUserLanguageVersion(): ?string
    {
        $switchToVersion = null;
        if ($_COOKIE[LanguageHelper::LANGUAGE_VERSION_COOKIE]) {
            if ($_COOKIE[LanguageHelper::LANGUAGE_VERSION_COOKIE] != LanguageHelper::getLanguageVersion()) {
                $switchToVersion = $_COOKIE[LanguageHelper::LANGUAGE_VERSION_COOKIE];
            }
        } else {
            global $USER;
            $userId = $USER->getId();
            if ($userId) {
                $switchToVersion = (new AuthorizedUser())->setUserId($userId)->getUserSiteVersion();
            } else {
                $switchToVersion = (new NotAuthorizedUser())->getUserSiteVersion();
            }
        }

        return $switchToVersion;
    }
}

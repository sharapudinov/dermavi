<?php

namespace App\Core;

use App\Helpers\LanguageHelper;
use App\Helpers\SiteHelper;
use App\Models\User;
use CEvent;

/**
 * Класс, описывающий логику работы с почтовыми уведомлениями
 * Class Email
 *
 * @package App\Core
 */
class Email
{
    /**
     * Отправляет письмо пользователю
     *
     * @param string $eventName Символьный код почтового события
     * @param array|mixed[] $data Массив, описывающий данные для передачи в почтовый шаблон
     * @param bool $duplicate Флаг необходимости отправки копии письма
     * @param User|null $user Модель пользователя
     *
     * @return void
     */
    public static function sendMail(string $eventName, array $data, bool $duplicate = true, User $user = null): void
    {
        if ($user && $user->country) {
            /** @var array|string[] $userCountryInfo Массив, описывающий языковую версию сайта пользователя */
            $userCountryInfo = $user->country->getCountryLanguageInfo();
        }

        CEvent::SendImmediate(
            $eventName,
            $userCountryInfo['site_id'] ?? SiteHelper::getSiteIdByCurrentLanguage(),
            $data,
            $duplicate ? 'Y' : 'N',
            '',
            [],
            $userCountryInfo['language_id'] ?? LanguageHelper::getLanguageVersion()
        );
    }
}

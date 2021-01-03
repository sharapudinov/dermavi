<?php

namespace App\Core;

/**
 * Класс для работы с браузером
 * Class Browser
 * @package App\Core
 */
class Browser
{
    /** @var string $userAgent - Информация о браузере пользователя */
    private $userAgent;

    /**
     * Browser constructor.
     */
    public function __construct()
    {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Проверяем является ли браузер Internet Explorer'ом
     *
     * @return bool
     */
    public function isInternetExplorer(): bool
    {
        return (strstr($this->userAgent, 'Trident/7.0; rv:11.0')
            || strstr($this->userAgent, 'microsoft internet explorer')
            || strstr($this->userAgent, 'msie'));
    }
}

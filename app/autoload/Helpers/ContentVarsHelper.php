<?php

namespace App\Helpers;

use App\Core\SprintOptions\Contacts;

/**
 * Class ContentVarsHelper
 * Контент-переменные, выводимые на сайте
 *
 * @package App\Helpers
 */
class ContentVarsHelper
{
    /**
     * Контактный телефон для b2b / b2c.
     * Выводится в шапке
     *
     * @return string
     */
    public static function getCurrentEntityContactPhone(): string
    {
        return CatalogHelper::isB2c() ? Contacts::getContactPhoneB2c() : Contacts::getContactPhoneB2b();
    }
}

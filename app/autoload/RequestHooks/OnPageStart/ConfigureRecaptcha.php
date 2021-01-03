<?php

namespace App\RequestHooks\OnPageStart;

use App\Helpers\LanguageHelper;
use Arrilot\GoogleRecaptcha\Recaptcha;

/**
 * Конфигурирует google recaptcha 2
 */
class ConfigureRecaptcha
{
    /**
     * Основной обработчик хука.
     */
    public static function handle()
    {
        Recaptcha::getInstance()
            ->setPublicKey(config('google.recaptcha.publicKey'))
            ->setSecretKey(config('google.recaptcha.secretKey'))
            ->setLanguage(LanguageHelper::getLanguageVersion());
    }
}

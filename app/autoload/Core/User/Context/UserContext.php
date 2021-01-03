<?php

namespace App\Core\User\Context;

use App\Core\Currency\Currency;
use App\Core\Currency\Entity\CurrencyEntity;
use App\Core\Exceptions\CurrencyNotFoundException;
use App\Helpers\LanguageHelper;
use App\Helpers\UserHelper;

/**
 * Class UserContext
 * Контекст пользователя (посетителя)
 *
 * @package App\Core\User\Context
 */
class UserContext
{
    /** @var static */
    private static $instance;

    /** @var string */
    private $instanceHash;

    /** @var string Символьный код текущей валюта контекста */
    protected $currency;

    /** @var bool Является ли текущий пользователь юридическим лицом */
    protected $isLegalEntity;

    /** @var string Символьный код языковой версии сайта */
    protected $languageVersion;

    public function __construct()
    {
        $this->instanceHash = uniqid('user_context_', true);
    }

    /**
     * @return string
     */
    protected function getInstanceHash(): string
    {
        return $this->instanceHash;
    }

    /**
     * Возвращает контекст текущего посетителя
     *
     * @return static
     */
    public static function getCurrent()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Является ли текущий экземпляр контекстом текущего посетителя
     *
     * @return bool
     */
    public function isCurrent(): bool
    {
        return static::getCurrent()->getInstanceHash() === $this->getInstanceHash();
    }

    /**
     * Текущая валюта, выбранная пользователем
     *
     * @return string
     */
    public function getCurrency(): string
    {
        if ($this->currency === null) {
            $this->currency = Currency::getCurrentCurrency()->getSymCode();
        }

        return $this->currency;
    }

    /**
     * @return CurrencyEntity
     * @throws CurrencyNotFoundException
     */
    public function getCurrencyInstance(): CurrencyEntity
    {
        $currencyCode = $this->getCurrency();
        $currencyInstance = (new Currency())->getCurrencyByAlphabetCode($currencyCode);
        if (!$currencyInstance) {
            throw new CurrencyNotFoundException('Currency ' . $currencyCode . ' not found');
        }

        return $currencyInstance;
    }

    /**
     * @param string $selectedCurrency
     * @return static
     */
    public function setCurrency(string $selectedCurrency)
    {
        $this->currency = $selectedCurrency;

        return $this;
    }

    /**
     * Является ли текущий пользователь юридическим лицом
     *
     * @return bool
     */
    public function isLegalEntity(): bool
    {
        if ($this->isLegalEntity === null) {
            $this->isLegalEntity = UserHelper::isLegalEntity();
        }

        return $this->isLegalEntity;
    }

    /**
     * @param bool $isLegalEntity
     * @return static
     */
    public function setIsLegalEntity(bool $isLegalEntity)
    {
        $this->isLegalEntity = $isLegalEntity;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguageVersion(): string
    {
        if ($this->languageVersion === null) {
            $this->languageVersion = LanguageHelper::getLanguageVersion();
        }

        return $this->languageVersion;
    }

    /**
     * @param string $languageVersion
     * @return static
     */
    public function setLanguageVersion(string $languageVersion)
    {
        $this->languageVersion = LanguageHelper::isValidLanguage($languageVersion)
            ? $languageVersion
            : LanguageHelper::DEFAULT_LANGUAGE;

        return $this;
    }
}

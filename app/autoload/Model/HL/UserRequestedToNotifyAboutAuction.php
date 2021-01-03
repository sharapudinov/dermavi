<?php

namespace App\Models\HL;

use App\Models\Auxiliary\HlD7Model;
use App\Models\User;
use Arrilot\BitrixModels\Queries\BaseQuery;

/**
 * Класс-модель для описания сущности "Пользователь, запросивший уведомление о начале аукциона"
 * Class UserRequestedToNotifyAboutAuction
 *
 * @package App\Models\HL
 *
 * @property-read User user
 */
class UserRequestedToNotifyAboutAuction extends HlD7Model
{
    /** @var string - Символьный код таблицы */
    protected static $tableName = 'app_users_requested_to_notificate_about_auction';

    /**
     * Получает email пользователя
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this['UF_EMAIL'];
    }

    /**
     * Получает идентификатор аукциона
     *
     * @return int
     */
    public function getAuctionId(): int
    {
        return $this['UF_AUCTION_ID'];
    }

    /**
     * Возвращает имя пользователя
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this['UF_NAME'];
    }

    /**
     * Возвращает фамилию пользователя
     *
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this['UF_SURNAME'];
    }

    /**
     * Возвращает телефон пользователя
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this['UF_PHONE'];
    }

    /**
     * Возвращает название компании
     *
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this['UF_COMPANY_NAME'];
    }

    /**
     * Возвращает страну
     *
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this['UF_COUNTRY'];
    }

    /**
     * Возвращает сферу деятельности компании
     *
     * @return string|null
     */
    public function getCompanyActivity(): ?string
    {
        return $this['UF_COMPANY_ACTIVITY'];
    }

    /**
     * Возвращает ИНН
     *
     * @return string|null
     */
    public function getTaxId(): ?string
    {
        return $this['UF_TAX_ID'];
    }

    /**
     * Возвращает запрос для получения модели пользователя
     *
     * @return BaseQuery
     */
    public function user(): BaseQuery
    {
        return $this->hasOne(User::class, 'EMAIL', 'UF_EMAIL');
    }
}

<?php

namespace App\Models\HL;

use App\Models\User;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;

/**
 * Класс-модель, описывающий сущность "Пользователь, запросивший изменение профиля в crm"
 * Class UserRequestCrmChange
 *
 * @package App\Models\HL
 *
 * @property-read User $user
 */
class UserRequestCrmChange extends D7Model
{
    /** @var string Символьный код таблицы */
    public const TABLE_CODE = 'app_user_request_crm_change';

    /** @var string Ключ кеширования */
    public const CACHE_KEY = 'SendUpdatedUsersToCrm';

    /**
     * Получает класс таблицы
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * Возвращает запрос для получения модели пользователя
     *
     * @return BaseQuery
     */
    public function user(): BaseQuery
    {
        return $this->hasOne(User::class, 'ID', 'UF_USER_ID');
    }
}

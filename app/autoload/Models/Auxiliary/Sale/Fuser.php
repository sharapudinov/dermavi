<?php

namespace App\Models\Auxiliary\Sale;

use App\Models\User;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Bitrix\Sale\Internals\FuserTable;

/**
 * @property User|null $user
 */
class Fuser extends D7Model
{
    /** @var string Имя класса таблицы */
    public const TABLE_CLASS = FuserTable::class;

    /**
     * Получает пользователя, оформлявшего заказ.
     *
     * @return BaseQuery
     */
    public function user(): BaseQuery
    {
        return $this->hasOne(User::class, 'ID', 'USER_ID');
    }
}

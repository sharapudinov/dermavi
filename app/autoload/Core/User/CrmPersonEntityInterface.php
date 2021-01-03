<?php

namespace App\Core\User;

use App\Models\User;
use stdClass;

/**
 * Интерфейс для работы с объектами, описывающими типы лиц для CRM
 * Interface CrmPersonEntityInterface
 * @package App\Core\User
 */
interface CrmPersonEntityInterface
{
    /**
     * Записывает в объект, описывающий сущность, данные на основе объекта пользователя из CRM
     *
     * @param stdClass $crmUser - Объект из CRM, описывающий пользователя
     *
     * @return CrmPersonEntityInterface
     */
    public function fillFromCrmUser(stdClass $crmUser): self;

    /**
     * Записывает в объект, описывающий сущность, данные на основе модели пользователя из БД ИМ
     *
     * @param User $user - Модель, описывающая пользователя в БД ИМ
     * @param bool $fullData - Флаг того, что необходим полный набор данных (false используется при регистрации)
     *
     * @return CrmPersonEntityInterface
     */
    public function fillFromDatabaseUser(User $user, bool $fullData = true): self;
}

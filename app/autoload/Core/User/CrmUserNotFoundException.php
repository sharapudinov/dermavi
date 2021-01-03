<?php

namespace App\Core\User;

use App\Models\User;
use Exception;

/**
 * Класс для обработки исключения о ненайденном в crm пользователе
 * Class CrmUserNotFoundException
 * @package App\Core\User
 */
class CrmUserNotFoundException extends Exception
{
    /**
     * CrmUserNotFoundException constructor.
     *
     * @param User $user - Модель пользователя
     * @param string $method - Метод, по которому был запрос в crm
     */
    public function __construct(User $user, string $method)
    {
        $message = 'User crmId ' . $user->getCrmId() . ' was not found in crm by method ' . $method;
        logger('crm')->error($message);

        parent::__construct($message);
    }
}

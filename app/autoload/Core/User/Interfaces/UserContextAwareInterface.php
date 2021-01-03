<?php

namespace App\Core\User\Interfaces;

use App\Core\User\Context\UserContext;

/**
 * Interface UserContextAwareInterface
 *
 * @package App\Core\User\Interfaces
 */
interface UserContextAwareInterface
{
    /**
     * @return UserContext
     */
    public function getUserContext(): UserContext;

    /**
     * @param UserContext $userContext
     * @return mixed
     */
    public function setUserContext(UserContext $userContext);
}

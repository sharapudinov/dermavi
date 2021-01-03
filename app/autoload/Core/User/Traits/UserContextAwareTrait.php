<?php

namespace App\Core\User\Traits;

use App\Core\User\Context\UserContext;

/**
 * Trait UserContextAwareTrait
 * Реализация UserContextAwareInterface
 *
 * @package App\Core\User\Traits
 */
trait UserContextAwareTrait
{
    /** @var UserContext */
    protected $userContextInstance;

    /**
     * Контекст пользователя
     *
     * @return UserContext
     */
    public function getUserContext(): UserContext
    {
        return $this->userContextInstance ?? UserContext::getCurrent();
    }

    /**
     * @param UserContext $userContext
     * @return static
     */
    public function setUserContext(UserContext $userContext)
    {
        $this->userContextInstance = $userContext;

        return $this;
    }
}

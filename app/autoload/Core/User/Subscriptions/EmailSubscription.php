<?php

namespace App\Core\User\Subscriptions;

use App\Helpers\TTL;
use App\Models\User;
use Exception;

/**
 * Класс, описывающий логику оформления подписки на email
 *
 * Class EmailSubscription
 *
 * @package App\Core\User\Subscriptions
 */
class EmailSubscription extends Subscription
{
    /** @var string $email Email, на который надо оформить подписку */
    private $email;

    /**
     * EmailSubscription constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Записывает в объект email, на который надо оформить подписку
     *
     * @param string $email Email
     *
     * @return EmailSubscription
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Добавляет подписку
     *
     * @return void
     *
     * @throws Exception
     */
    public function add(): void
    {
        /** @var User|null $user Модель пользователя */
        $user = User::cache(TTL::DAY)->getByEmail($this->email);

        if ($user) {
            (new UserSubscription())->setUser($user)->add();
        } else {
            $this->fields = ['EMAIL' => $this->email];
            parent::add();
        }
    }

    /**
     * Обновляет подписку
     *
     * @param int $subscriptionId Идентификатор подписки
     *
     * @return void
     *
     * @throws Exception
     */
    public function edit(int $subscriptionId): void
    {
        /** @var User|null $user Модель пользователя */
        $user = User::cache(TTL::DAY)->getByEmail($this->email);

        if ($user) {
            (new UserSubscription())->setUser($user)->setTypes($this->getTypes())->edit($subscriptionId);
        } else {
            $this->fields = ['EMAIL' => $this->email];
            parent::edit($subscriptionId);
        }
    }
}

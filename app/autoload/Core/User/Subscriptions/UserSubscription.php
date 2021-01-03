<?php

namespace App\Core\User\Subscriptions;

use App\Core\BitrixEvent\EventMessage;
use App\Helpers\LanguageHelper;
use App\Helpers\SiteHelper;
use App\Models\User;
use CEvent;
use Exception;

/**
 * Класс, описывающий логику оформления подписки на пользователя
 * Class UserSubscription
 *
 * @package App\Core\User\Subscriptions
 */
class UserSubscription extends Subscription
{
    /** @var User $user Модель пользователя */
    private $user;

    /**
     * UserSubscription constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Устанавливает свойства подписки по-умолчанию
     *
     * @return void
     */
    private function initFields(): void
    {
        $this->fields = ['USER_ID' => $this->user->getId(), 'EMAIL' => $this->user->getEmail()];
    }

    /**
     * Записывает в объект пользователя, которому надо оформить подписку
     *
     * @param User $user Модель пользователя
     *
     * @return UserSubscription
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Добавляет подписку
     *
     * @param bool $sendEmail Флаг необходимости отправки письма
     *
     * @return void
     */
    public function add(bool $sendEmail = true): void
    {
        $this->initFields();
        parent::add();

        if ($sendEmail) {
            $this->sendEmail();
        }
    }

    /**
     * Обновляет подписку
     *
     * @param int $subscriptionId Идентификатор подписки
     * @param bool $sendEmail Флаг необходимости отправки письма
     *
     * @return void
     */
    public function edit(int $subscriptionId, bool $sendEmail = true): void
    {
        $this->initFields();

        parent::edit($subscriptionId);

        if ($sendEmail) {
            $this->sendEmail();
        }
    }

    /**
     * Отправляет письмо об изменении подписки
     *
     * @return void
     */
    public function sendEmail(): void
    {
        $eventMessage = EventMessage::getEventMessagesByCode('SUBSCRIPTION_STATUS', LanguageHelper::getLanguageVersion())->first();
        CEvent::SendImmediate('SUBSCRIPTION_STATUS', SiteHelper::getSiteIdByCurrentLanguage(), [
            'EMAIL_TO' => $this->user->getEmail(),
        ], 'Y', $eventMessage->getMessageId(), [], LanguageHelper::getLanguageVersion());
    }
}

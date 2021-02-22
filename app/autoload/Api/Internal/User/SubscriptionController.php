<?php

namespace App\Api\Internal\User;

use App\Api\BaseController;
use App\Core\User\Subscriptions\EmailSubscription;
use App\Core\User\Subscriptions\Subscription;
use App\Core\User\Subscriptions\SubscriptionType;
use App\Core\User\Subscriptions\UserSubscription;
use Arrilot\BitrixCacher\Cache;
use CSubscription;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Класс-контроллер для работы с пользователем
 * Class SubscriptionController
 *
 * @package App\Api\Internal\User
 */
class SubscriptionController extends BaseController
{
    /**
     * Оформляем пользователю маркетинговую рассылку
     *
     * @return ResponseInterface
     */
    public function addUserToMarketingSubscription(): ResponseInterface
    {
        /** @var string $email - Email пользователя для оформления подписки */
        $email = e($this->getParam('email'));

        /** @var ResponseInterface $response Ответ сервера */
        $response = null;

        try {
            /** @var int $newsAndAdvertising Идентификатор новостной подписки */
            $newsAndAdvertising = array_column(
                (new SubscriptionType())->getByCodes([SubscriptionType::NEWS_AND_ADVERTISING]),
                'ID'
            );

            /** @var Subscription $subscriptionObject Экземпляр класса для работы с подпиской пользователя */
            $subscriptionObject = new Subscription();
            $subscription = $subscriptionObject->get(['EMAIL' => $email]);
            $emailSubscriptionObject = (new EmailSubscription())->setTypes(
                array_merge($subscriptionObject->getTypes(), $newsAndAdvertising)
            )->setEmail($email);

            if ($subscription) {
                $emailSubscriptionObject->edit($subscription['ID']);

                Cache::flush(Subscription::SUBSCRIPTION_CACHE . user()->getId());
            } else {
                $emailSubscriptionObject->add();
            }

            $response = $this->respondWithSuccess();
        } catch (Throwable $exception) {
            $this->writeErrorLog(self::class, $exception);
            $response = $this->respondWithError();
        } finally {
            return $response;
        }
    }

    /**
     * Осуществляет подписку для пользователя
     *
     * @return ResponseInterface
     */
    public function add(): ResponseInterface
    {
        /** @var ResponseInterface $response Ответ сервера */
        $response = null;

        try {
            /** @var array|string[] $chosenSubscriptionTypes Массив символьных кодов выбранных типов подписок */
            $chosenSubscriptionTypes = htmlentities_on_array($this->getParam('subscriptions'));
            $chosenSubscriptionTypesIds = array_column(
                (new SubscriptionType())->getByCodes($chosenSubscriptionTypes),
                'ID'
            );

            (new UserSubscription())->setTypes($chosenSubscriptionTypesIds)->setUser(user())->add();
            Cache::flush(Subscription::SUBSCRIPTION_CACHE . user()->getId());

            $response = $this->respondWithSuccess();
        } catch (Throwable $exception) {
            $this->writeErrorLog(self::class, $exception);
            $response = $this->respondWithError();
        } finally {
            return $response;
        }
    }

    /**
     * Изменяет выбранные типы подписок для пользователя
     *
     * @return ResponseInterface
     */
    public function edit(): ResponseInterface
    {
        /** @var ResponseInterface $response Ответ сервера */
        $response = null;

        try {
            /** @var array|string[] $chosenSubscriptionTypes Массив символьных кодов выбранных типов подписок */
            $chosenSubscriptionTypes = htmlentities_on_array($this->getParam('subscriptions'));

            $chosenSubscriptionTypesIds = array_column(
                (new SubscriptionType())->getByCodes($chosenSubscriptionTypes),
                'ID'
            );
            $subscription = (new Subscription())->get(['USER_ID' => user()->getId()]);

            (new UserSubscription())->setTypes($chosenSubscriptionTypesIds)->setUser(user())->edit($subscription['ID']);
            Cache::flush(Subscription::SUBSCRIPTION_CACHE . user()->getId());

            $response = $this->respondWithSuccess();
        } catch (Throwable $exception) {
            $this->writeErrorLog(self::class, $exception);
            $response = $this->respondWithError();
        } finally {
            return $response;
        }
    }
}

<?php

namespace App\Core\User\Subscriptions;

use App\Helpers\TTL;
use App\Models\User;
use Bitrix\Main\Loader;
use CSubscription;
use Exception;

Loader::IncludeModule('subscribe');

/**
 * Класс для работы с подписками пользователя
 * Class Subscription
 *
 * @package App\Core\User
 */
class Subscription
{
    /** @var string Часть ключа кеша раздела подписок */
    public const SUBSCRIPTION_CACHE = 'subscription_section_';

    /** @var array|mixed[] $subscriptionInfo Информация о подписке */
    private $subscriptionInfo;

    /** @var array|int[] $types Массив идентификаторов типов рассылок */
    private $types;

    /** @var array|mixed[] $fields Массив полей подписки */
    protected $fields = [];

    /**
     * Subscription constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        if (!method_exists(get_called_class(), 'add')) {
            throw new Exception('Необходимо переобпределить метод add');
        }

        if (!method_exists(get_called_class(), 'edit')) {
            throw new Exception('Необходимо переобпределить метод edit');
        }
    }

    /**
     * Проверяет подписан ли пользователь на рассылки
     *
     * @param string $email - Email пользователя
     * @return bool
     */
    public static function isUserSubscribed(string $email): bool
    {
        return cache($email, TTL::DAY, function () use ($email) {
            return CSubscription::GetByEmail($email)->Fetch() !== false;
        });
    }

    /**
     * Добавляет подписку
     *
     * @return void
     */
    protected function add(): void
    {
        (new CSubscription())->Add(array_merge($this->fields, ['RUB_ID' => $this->types]));
    }

    /**
     * Обновляет подписку
     *
     * @param int $subscriptionId Идентификатор подписки
     *
     * @return void
     */
    protected function edit(int $subscriptionId): void
    {
        (new CSubscription())->Update($subscriptionId, array_merge($this->fields, ['RUB_ID' => $this->types]));
    }

    /**
     * Возвращает список подписок пользователя
     *
     * @param User $user Модель пользователя
     *
     * @return array|int[]
     *
     * @throws Exception
     */
    public function getUserSubscriptionsList(User $user): array
    {
        /** @var array|int[] $userSubscriptions Массив идентификаторов типов подписок пользователя */
        $userSubscriptions = [];

        $this->get(['USER_ID' => $user->getId()]);

        if ($this->subscriptionInfo) {
            $userSubscriptionsQuery = db()->query(
                'SELECT b_list_rubric.ID, b_list_rubric.CODE FROM b_list_rubric '
                . 'JOIN b_subscription_rubric ON b_subscription_rubric.LIST_RUBRIC_ID = b_list_rubric.ID '
                . 'JOIN b_subscription ON b_subscription.ID = b_subscription_rubric.SUBSCRIPTION_ID '
                . 'WHERE b_subscription.ID = ' . $this->subscriptionInfo['ID'] . ';'
            );

            while ($subscriptionType = $userSubscriptionsQuery->fetch()) {
                $userSubscriptions[] = $subscriptionType['CODE'];
            }
        }

        return $userSubscriptions;
    }

    /**
     * Возвращает подписку по фильтру
     *
     * @param array|mixed[] $filter
     *
     * @return null|array|mixed[]
     */
    public function get(array $filter): ?array
    {
        $subscriptionInfo = (new CSubscription())->GetList([], $filter)->Fetch();

        if (!$subscriptionInfo) {
            $subscriptionInfo = null;
        }

        $this->subscriptionInfo = $subscriptionInfo;
        return $this->subscriptionInfo;
    }

    /**
     * Записывает определенные типы рассылок
     *
     * @param array|int[] $types Массив идентификаторов типов рассылок
     *
     * @return static
     */
    public function setTypes(array $types): self
    {
        $this->types = $types;
        return $this;
    }

    /**
     * Возвращает массив идентификаторов типов рассылок
     *
     * @return array|int[]
     */
    public function getTypes(): array
    {
        if (!$this->types) {
            $this->types = CSubscription::GetRubricArray($this->subscriptionInfo['ID']);
        }

        return $this->types;
    }

    /**
     * Записывает все типы рассылок
     *
     * @return static
     */
    public function setAllTypes(): self
    {
        $this->types = array_column(
            (new SubscriptionType())->getByCodes([
                SubscriptionType::NEWS_AND_ADVERTISING,
                SubscriptionType::MARKETING_AND_ACTIONS,
                SubscriptionType::AUCTIONS_REMIND
            ]),
            'ID'
        );
        return $this;
    }

    /**
     * Возвращает информацию о подписке пользователя
     *
     * @return null|array|mixed[]
     */
    public function getSubscriptionInfo(): ?array
    {
        return $this->subscriptionInfo;
    }
}

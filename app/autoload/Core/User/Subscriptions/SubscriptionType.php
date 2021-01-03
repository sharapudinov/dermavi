<?php

namespace App\Core\User\Subscriptions;

use Bitrix\Main\Loader;
use CRubric;

Loader::IncludeModule('subscribe');

/**
 * Класс, реализующий логику работы с типами подписок
 * Class SubscriptionType
 *
 * @package App\Core\User\Subscriptions
 */
class SubscriptionType extends CRubric
{
    /** @var string Символьный код рассылки "Новости и реклама" */
    public const NEWS_AND_ADVERTISING = 'news_and_advertising';

    /** @var string Символьный код рассылки "Маркетинг и акции" */
    public const MARKETING_AND_ACTIONS = 'marketing_and_actions';

    /** @var string Символьный код рассылки "Напоминание об аукционах" */
    public const AUCTIONS_REMIND = 'auctions_remind';

    /**
     * Возвращает список типов подписок на основе их символьных кодов
     *
     * @param array|string[] $codes
     *
     * @return array|array[]
     */
    public function getByCodes(array $codes): array
    {
        $whereCondition = '';
        for ($i = 0; $i < count($codes); $i++) {
            if ($i == 0) {
                $whereCondition .= 'WHERE';
            } else {
                $whereCondition .= 'OR';
            }

            $whereCondition .= ' CODE = "' . $codes[$i] . '" ';
        }

        $rubrics = [];

        if ($codes) {
            $rubricsQuery = db()->query('SELECT * FROM b_list_rubric ' . $whereCondition);
            while ($rubric = $rubricsQuery->fetch()) {
                $rubrics[] = $rubric;
            }
        }

        return $rubrics;
    }
}

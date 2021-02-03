<?php

namespace App\Models\Auxiliary\Subscriptions;

use Arrilot\BitrixModels\Models\D7Model;
use Bitrix\Main\Loader;
use CSubscription;

/**
 * Класс-модель, описывающий сущность "Подписка"
 * Class Subscription
 *
 * @package App\Models\Auxiliary
 */
class Subscription extends D7Model
{
    /** @var string Имя класса таблицы */
    public const TABLE_CLASS = CSubscription::class;

    /**
     * Subscription constructor.
     *
     * @param null $id
     * @param null $fields
     */
    public function __construct($id = null, $fields = null)
    {
        Loader::IncludeModule('subscribe');
        parent::__construct($id, $fields);
    }
}

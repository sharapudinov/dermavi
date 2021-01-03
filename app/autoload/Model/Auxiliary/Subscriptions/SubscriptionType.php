<?php

namespace App\Models\Auxiliary\Subscriptions;

use Arrilot\BitrixModels\Models\D7Model;
use Bitrix\Main\Loader;
use CRubric;

/**
 * Класс-модель, описывающий сущность "Тип подписки"
 * Class SubscriptionType
 *
 * @package App\Models\Auxiliary\Subscriptions
 */
class SubscriptionType extends D7Model
{
    /** @var string Имя класса таблицы */
    public const TABLE_CLASS = CRubric::class;

    /**
     * SubscriptionType constructor.
     *
     * @param null $id
     * @param null $fields
     */
    public function __construct($id = null, $fields = null)
    {
        Loader::IncludeModule('subscribe');
        parent::__construct($id, $fields);
    }

    /**
     * Возвращает идентификатор
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Возвращает символьный код
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this['CODE'];
    }

    /**
     * Возвращает название
     *
     * @return string
     */
    public function getName(): string
    {
        return $this['NAME'];
    }
}

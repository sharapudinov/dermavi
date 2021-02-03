<?php

namespace App\Models\Auxiliary\CRM;

use Arrilot\BitrixModels\Models\EloquentModel;

/**
 * Класс-модель для описания сущности "Семейное положение в CRM"
 * Class FamilyStatus
 * @package App\Models\Auxiliary\CRM
 */
class FamilyStatus extends EloquentModel
{
    /** @var string $table - Символьный код таблицы */
    protected $table = 'app_crm_family_status';

    /** @var bool $timestamps - Флаг использования полей с датой/временем */
    public $timestamps = false;

    /**
     * Возвращает идентификатор статуса в crm
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this['code'];
    }

    /**
     * Проверяет является ли статус "Женат"
     *
     * @return bool
     */
    public function isMarried(): bool
    {
        return $this->getCode() == 'Married';
    }
}

<?php

namespace App\Models\Auxiliary\CRM;

use App\Helpers\LanguageHelper;
use Arrilot\BitrixModels\Models\EloquentModel;

/**
 * Класс-модель для сущности "Регион"
 * Class Region
 * @package App\Models\Auxiliary\CRM
 */
class Region extends EloquentModel
{
    /** @var string $table - Символьный код таблицы */
    public $table = 'app_crm_region';

    /** @var bool $timestamps - Флаг использования полей с датой/временем */
    public $timestamps = false;

    /**
     * Возвращает идентификатор региона в БД
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['id'];
    }

    /**
     * Возвращает crmId региона
     *
     * @return string
     */
    public function getCrmId(): string
    {
        return $this['code'];
    }

    /**
     * Возвращает название региона
     *
     * @return string
     */
    public function getValue(): string
    {
        return LanguageHelper::getTableMultilingualFieldValue($this, 'name');
    }

    /**
     * Возвращает идентификатор страны, которой принадлежит регион
     *
     * @return int
     */
    public function getCountryId(): int
    {
        return $this['country_id'];
    }
}

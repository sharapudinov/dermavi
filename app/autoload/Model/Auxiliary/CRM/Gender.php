<?php

namespace App\Models\Auxiliary\CRM;

use App\Helpers\GenderHelper;
use App\Models\HL\SalutationType;
use Arrilot\BitrixModels\Models\EloquentModel;

/**
 * Класс-модель, описывающий сущность "Пол пользователя в CRM"
 * Class Gender
 * @package App\Models\Auxiliary\CRM
 */
class Gender extends EloquentModel
{
    /** @var string $table - Символьный код таблицы */
    protected $table = 'app_crm_gender';

    /** @var bool $timestamps - Флаг использования полей с датой/временем */
    public $timestamps = false;

    /**
     * Получает результат по обращению
     *
     * @param SalutationType $salutationType Тип обращения
     *
     * @return Gender
     */
    public static function getByAppeal(SalutationType $salutationType): self
    {
        return self::where(['gender_value' => GenderHelper::getGenderByAppeal($salutationType)])->first();
    }

    /**
     * Получает идентификатор пола в crm
     *
     * @return string
     */
    public function getXmlId(): string
    {
        return $this['code'];
    }

    /**
     * Получает значение пола в crm
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this['gender_value'];
    }

    /**
     * Проверяет является ли пол мужским
     *
     * @return bool
     */
    public function isMale(): bool
    {
        return $this->getValue() == 'Мужской';
    }
}

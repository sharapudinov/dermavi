<?php

namespace App\Models\Auxiliary\CRM;

use App\Helpers\TTL;
use Arrilot\BitrixModels\Models\EloquentModel;

/**
 * Класс-модель, описывающий сущность "Другое средство связи"
 * Class OtherContactType
 *
 * @package App\Models\Auxiliary\CRM
 */
class OtherContactType extends EloquentModel
{
    /** @var string $table - Символьный код таблицы */
    protected $table = 'other_contact_type';

    /** @var bool $timestamps Флаг использования полей с датой/временем */
    public $timestamps = false;

    /** @var string Названия типов */
    private const ADDITIONAL_PHONE = 'Дополнительный телефон';
    private const EMAIL = 'Email';

    /**
     * Возвращает модель по имени типа
     *
     * @param string $name Название типа
     *
     * @return OtherContactType|null
     */
    private static function getByName(string $name): ?self
    {
        return cache(get_class_name_without_namespace(self::class), TTL::DAY, function () use ($name) {
            return OtherContactType::where('value', $name)->first();
        });
    }

    /**
     * Возвращает модель дополнительного телефона
     *
     * @return OtherContactType
     */
    public static function getAdditionalPhone(): self
    {
        return self::getByName(self::ADDITIONAL_PHONE);
    }

    /**
     * Возвращает модель дополнительной электронной почты
     *
     * @return OtherContactType
     */
    public static function getAdditionalEmail(): self
    {
        return self::getByName(self::EMAIL);
    }

    /**
     * Возвращает внешний код
     *
     * @return string
     */
    public function getXmlId(): string
    {
        return $this['code'];
    }
}

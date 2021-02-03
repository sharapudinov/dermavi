<?php

namespace App\Models\HL;

use Arrilot\BitrixModels\Models\D7Model;

/**
 * Класс-модель, описывающий сущность "Обращение"
 * Class SalutationType
 *
 * @package App\Models\HL\SalutationType
 */
class SalutationType extends D7Model
{
    /** @var string Символьный код таблицы */
    public const TABLE_CODE = 'app_salutation_type';

    /**
     * Получает класс таблицы
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * Возвращает идентификатор обращения
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Возвращает тип обращения
     *
     * @return string
     */
    public function getXmlId(): string
    {
        return $this['UF_XML_ID'];
    }

    /**
     * Возвращает значение обращения
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this['UF_VALUE'];
    }
}

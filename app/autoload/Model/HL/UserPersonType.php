<?php

namespace App\Models\HL;

use Arrilot\BitrixModels\Models\D7Model;

/**
 * Класс-модель, описывающий сущность "Тип лица"
 * Class UserPersonType
 *
 * @package App\Models\HL
 */
class UserPersonType extends D7Model
{
    /** @var string Символьный код таблицы */
    public const TABLE_CODE = 'app_user_person_type';

    /** @var string Название физ лица */
    public const PHYSIC_PERSON = 'Физическое';

    /** @var string Название юр лица */
    public const LEGAL_PERSON = 'Юридическое';

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
     * Возвращает идентификатор записи
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Возвращает внешний код
     *
     * @return string
     */
    public function getXmlId(): string
    {
        return $this['UF_XML_ID'];
    }

    /**
     * Возвращает имя типа лица
     *
     * @return string
     */
    public function getName(): string
    {
        return $this['UF_VALUE'];
    }
}

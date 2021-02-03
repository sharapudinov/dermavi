<?php

namespace App\Models\HL\Company;

use Arrilot\BitrixModels\Models\D7Model;

/**
 * Класс-модель, описывающий сущность "Категория юр лица"
 * Class CompanyCategory
 *
 * @package App\Models\HL\Company
 */
class CompanyCategory extends D7Model
{
    /** @var string Символьный код таблицы */
    public const TABLE_CODE = 'individual_company_category';

    /**
     * Возвращает класс таблицы
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
}

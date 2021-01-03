<?php

namespace App\Models\HL\Company;

use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;

/**
 * Класс-модель, описывающий сущность "Тип компании" (Юр лицо|Индивидуальный предприниматель)
 * Class CompanyType
 *
 * @package App\Models\HL\Company
 */
class CompanyType extends D7Model
{
    /** @var string Символьный код таблицы */
    public const TABLE_CODE = 'company_type';

    /** @var string Название типа юр лица */
    public const LEGAL_ENTITY = 'Юридическое лицо';

    /** @var string Название типа ИП */
    public const INDIVIDUAL_ENTITY = 'Индивидуальный предприниматель';

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
     * Возвращает идентификатор
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Возвращает название типа
     *
     * @return string
     */
    public function getName(): string
    {
        return $this['UF_NAME'];
    }
}

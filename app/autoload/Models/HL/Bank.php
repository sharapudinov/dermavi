<?php

namespace App\Models\HL;

use Arrilot\BitrixModels\Models\D7Model;

/**
 * Класс-модель для описания сущности "Банк"
 * Class Bank
 *
 * @package App\Models\HL
 */
class Bank extends D7Model
{
    /** @var string Символьный код таблицы */
    public const TABLE_CODE = 'app_bank';

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

    /**
     * Возвращает название банка
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this['UF_NAME'];
    }

    /**
     * Возвращает расчетный счет в банке
     *
     * @return string|null
     */
    public function getCheckingAccount(): ?string
    {
        return $this['UF_CHECK_ACCOUNT'];
    }

    /**
     * Возвращает БИК
     *
     * @return string|null
     */
    public function getBik(): ?string
    {
        return $this['UF_BIK'];
    }

    /**
     * Возвращает корр. счет
     *
     * @return string|null
     */
    public function getCorAccount(): ?string
    {
        return $this['UF_COR_ACCOUNT'];
    }

    /**
     * Возвращает ИНН
     *
     * @return string|null
     */
    public function getTaxId(): ?string
    {
        return $this['UF_TAX_ID'];
    }

    /**
     * Возвращает КПП
     *
     * @return string|null
     */
    public function getKPP(): ?string
    {
        return $this['UF_KPP'];
    }

    /**
     * Возвращает ОКПО
     *
     * @return string|null
     */
    public function getOKPO(): ?string
    {
        return $this['UF_OKPO'];
    }

    /**
     * Возвращает crm id записи
     *
     * @return string|null
     */
    public function getCrmId(): ?string
    {
        return $this['UF_CRM_ID'];
    }
}

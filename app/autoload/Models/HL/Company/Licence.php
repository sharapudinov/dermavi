<?php

namespace App\Models\HL\Company;

use Arrilot\BitrixModels\Models\D7Model;

/**
 * Класс-модель, описывающая сущность "Лицензия компании"
 * Class Licence
 *
 * @package App\Models\HL\Company
 */
class Licence extends D7Model
{
    /** @var string Символьный код таблицы */
    public const TABLE_CODE = 'licence';

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
     * Возвращает идентификатор записи в БД
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Получает наименование документа
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this['UF_TITLE'];
    }

    /**
     * Возвращает вид деятельности
     *
     * @return string|null
     */
    public function getActivity(): ?string
    {
        return $this['UF_ACTIVITY'];
    }

    /**
     * Возвращает серийный номер
     *
     * @return string|null
     */
    public function getSerialNumber(): ?string
    {
        return $this['UF_SERIAL_NUMBER'];
    }

    /**
     * Возвращает дату выдачи
     *
     * @return string|null
     */
    public function getIssueDate(): ?string
    {
        return $this['UF_ISSUE_DATE'] ? $this['UF_ISSUE_DATE']->format('d.m.Y') : null;
    }

    /**
     * Возвращает орган, выдавший документ
     *
     * @return string|null
     */
    public function getIssueAuthority(): ?string
    {
        return $this['UF_ISSUE_AUTHORITY'];
    }

    /**
     * Возвращает срок действия
     *
     * @return string|null
     */
    public function getValidTo(): ?string
    {
        return $this['UF_VALID_TO'] ? $this['UF_VALID_TO']->format('d.m.Y') : null;
    }
}

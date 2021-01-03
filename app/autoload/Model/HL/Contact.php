<?php

namespace App\Models\HL;

use App\Models\Auxiliary\HlD7Model;

/**
 * Класс-модель для сущности "Контакт"
 * Class Contact
 *
 * @package App\Models\HL
 */
class Contact extends HlD7Model
{
    /** @var string - Символьный код таблицы в БД */
    public const TABLE_CODE = 'app_contact';

    /**
     * Возвращает класс таблицы.
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * Возвращает crm id контакта
     *
     * @return string|null
     */
    public function getCrmId(): ?string
    {
        return $this['UF_CRM_ID'];
    }
}

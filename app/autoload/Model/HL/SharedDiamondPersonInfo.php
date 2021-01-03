<?php

namespace App\Models\HL;

use Arrilot\BitrixModels\Models\D7Model;

/**
 * Класс-модель для сущности "Информация о "поделившемся бриллиантом"
 * Class SharedDiamondPersonInfo
 * @package App\Models\HL
 */
class SharedDiamondPersonInfo extends D7Model
{
    /** @var string - Символьный код таблицы */
    const TABLE_CODE = 'app_shared_diamond_person';

    /**
     * Получает класс таблицы
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }
}

<?php

namespace App\Models;

use Arrilot\BitrixModels\Models\D7Model;

/**
 * Class BaseD7Model
 *
 * @package App\Models
 */
class BaseD7Model extends D7Model
{
    /** @var string */
    const TABLE_CODE = '';

    /**
     * Получаем класс таблицы
     *
     * @return string
     * @throws \Exception
     */
    public static function tableClass(): string
    {
        if (!static::TABLE_CODE) {
            throw new \Exception("Необходимо определить const TABLE_CODE");
        }

        return highloadblock_class(static::TABLE_CODE);
    }

    public static function getTableName(): string
    {
        return static::TABLE_CODE;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this['ID'];
    }
}

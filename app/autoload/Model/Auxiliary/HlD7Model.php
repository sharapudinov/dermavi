<?php
namespace App\Models\Auxiliary;

use Arrilot\BitrixModels\Models\D7Model;

/**
 * Базовый класс для моделей HL-блоков.
 * Class HlD7Model
 * @package App\Models\Auxiliary
 */
class HlD7Model extends D7Model
{
    /** @var string Название таблицы в БД */
    protected static $tableName;
    
    /**
     * Возвращает название таблицы для HL-блока.
     * @return string
     */
    public static function tableName(): string
    {
        return (string) static::$tableName;
    }
    
    /**
     * Возвращает класс таблицы.
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(static::tableName());
    }
    
    /**
     * Возвращает идентификатор элемента.
     * @return int
     */
    public function getId(): int
    {
        return (int) $this['ID'];
    }
}

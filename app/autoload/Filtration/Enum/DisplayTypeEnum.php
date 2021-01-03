<?php

namespace App\Filtration\Enum;

/**
 * Class DisplayTypeEnum
 *
 * @package App\Filtration\Enum
 */
final class DisplayTypeEnum
{
    public const RANGE = 'range';

    public const CHECKBOX = 'checkbox';

    public const RADIOBUTTON = 'radiobutton';

    public const SELECT = 'select';

    public const RANGE_VALUE_TYPE_INITIAL = 'initial';

    public const RANGE_VALUE_TYPE_FILTERED = 'filtered';

    /**
     * @param string $value
     * @return string
     */
    public static function convertFromBitrixValue(string $value): string
    {
        switch ($value) {
            case 'A':
                // Число от-до с ползунком
                $value = static::RANGE;
                break;

            case 'B':
                // Число от-до
                $value = static::RANGE;
                break;

            case 'F':
                // Флажки
                $value = static::CHECKBOX;
                break;

            case 'K':
                // Радиокнопки
                $value = static::RADIOBUTTON;
                break;

            case 'P':
                // Выпадающий список
                $value = static::SELECT;
                break;
        }

        return $value;
    }
}

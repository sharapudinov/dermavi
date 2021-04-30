<?php

namespace App\Core\Jewelry\Dto;

use App\Core\LazyLoadDto;
use App\Models\Jewelry\JewelryDiamond;

/**
 * Модель представления ЮИ.
 * Class JewelryViewModel
 *
 * @package App\Core\Sale\History
 * @property int    $count   Кол-во бриллиантов
 * @property string $shape   Форма
 * @property string $type    Тип
 * @property string $color   Цвет
 * @property string $clarity Чистота
 * @property string $cut     Качество огранки
 * @property float  $weight  Вес
 * @property string $number  Бирка
 */
class JewelryDiamondDto extends LazyLoadDto
{
    public function __construct(JewelryDiamond $diamond)
    {
        //Тут данные которые можно кешировать, остальные через LazyLoad
        $defaultValue        = '&mdash;';
        $attributes = [
            'number'  => $diamond->getExternalId(),
            'type'    => 'Бриллиант',
            'shape'   => $diamond->shape ? $diamond->shape->getDisplayValue() : $defaultValue,
            'color'   => $diamond->color ? $diamond->color->getDisplayValue() : $defaultValue,
            'clarity' => $diamond->clarity ? $diamond->clarity->getDisplayValue() : $defaultValue,
            'cut'     => $diamond->quality ? $diamond->quality->getDisplayValue() : $defaultValue,
            'weight'  => $diamond->getWeight(),
            'count'   => $diamond->getQuantity(),
        ];

        parent::__construct($attributes);
    }
}

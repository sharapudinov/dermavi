<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Models\Catalog\Diamond;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику определения соответствия бриллианта и заготовки по цвету +- одна группа
 * Class ColorSoftAccordance
 *
 * @package App\Core\Jewelry\Constructor\Generator
 */
class ColorSoftAccordance implements CombinationAccordanceInterface
{
    /**
     * Возвращает истину, если комбинация прошла проверку на возможность создания
     *
     * @param Collection|Diamond[] $diamonds Коллекция моделей бриллиантов текущей комбинации
     *
     * @return bool
     */
    public function isCombinationAccords(Collection $diamonds): bool
    {
        // если камень один, то он не может не подойти
        if ($diamonds->count() === 1) {
            return true;
        }

        return AccordanceHelper::isSoftAccordance(
            $diamonds->pluck('PROPERTY_COLOR_VALUE')->unique()->toArray(),
            ['d', 'e', 'f', 'g', 'h', 'i', 'j']
        );
    }
}

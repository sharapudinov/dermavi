<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Models\Catalog\Diamond;
use App\Models\Jewelry\JewelryBlank;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику определения соответствия бриллианта и заготовки по цвету
 * Class ColorAccordance
 *
 * @package App\Core\Jewelry\Constructor\Generator
 */
class ColorAccordance implements AccordanceInterface, CombinationAccordanceInterface
{
    /**
     * Возвращает коллекцию моделей бриллиантов, соответствующих заготовке
     *
     * @param JewelryBlank $blank Модель заготовки
     * @param Collection|Diamond[] $diamonds Коллекция моделей бриллиантов
     *
     * @return Collection|Diamond[]
     */
    public function getAccordingDiamonds(JewelryBlank $blank, Collection $diamonds): Collection
    {
        // Проверка на вес заготовки и что количество бриллиантов с одним и тем же цветом не меньше количества слотов
        $groupedDiamonds = $diamonds->groupBy('PROPERTY_COLOR_VALUE');
        return AccordanceHelper::getDiamondsForCharacteristic($blank, $groupedDiamonds);
    }

    /**
     * Возвращает истину, если комбинация прошла проверку на возможность создания
     *
     * @param Collection|Diamond[] $diamonds Коллекция моделей бриллиантов текущей комбинации
     *
     * @return bool
     */
    public function isCombinationAccords(Collection $diamonds): bool
    {
        // Проверка на то, что у всех бриллиантов в комбинации одинаковый цвет
        return $diamonds->pluck('PROPERTY_COLOR_VALUE')->unique()->count() == 1;
    }
}

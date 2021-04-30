<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Models\Catalog\Diamond;
use App\Models\Jewelry\JewelryBlank;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику определения соответствия бриллианта и заготовки по разнице в весах
 * Class ColorAccordance
 *
 * @package App\Core\Jewelry\Constructor\Generator
 */
class WeightAccordance implements CombinationAccordanceTypeInterface
{
    /**
     * Возвращает истину, если комбинация может быть создана
     *
     * @param JewelryBlank $blank Модель заготовки
     * @param Collection|Diamond[] $diamonds Коллекция моделей
     *
     * @return bool
     */
    public function isTypeAccords(JewelryBlank $blank, Collection $diamonds): bool
    {
        if (!$blank->isRelationByWeight()) {
            return true;
        }

        // Проверка на то, что вес бриллиантов в комбинации отличается не более, чем на 5%
        $weights = $diamonds->pluck('PROPERTY_WEIGHT_VALUE')->sortBy(static function (string $weight) {
            return (float) $weight;
        });

        $fromRange = false;
        foreach ($blank->casts as $cast) {
            $fromRange = true;
            foreach ($cast->ranges as $range) {
                foreach ($weights as $weight) {
                    if ((float) $weight < (float) $range->getFrom()
                        || (float) $weight > (float) $range->getTo()) {
                        $fromRange = false;
                        break;
                    }
                }
            }
        }

        if (!$fromRange) {
            return false;
        }

        return 100 - ($weights->first() / $weights->last() * 100) <= 5;
    }
}

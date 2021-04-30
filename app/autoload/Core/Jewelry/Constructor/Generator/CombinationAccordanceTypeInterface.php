<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Models\Catalog\Diamond;
use App\Models\Jewelry\JewelryBlank;
use Illuminate\Support\Collection;

/**
 * Интерфейс, описывающий методы определения соответствий комбинаций и заготовки там, где нужно понимать тип подбора
 * Interface CombinationAccordanceTypeInterface
 *
 * @package App\Core\Jewelry\Constructor\Generator
 */
interface CombinationAccordanceTypeInterface
{
    /**
     * Возвращает истину, если комбинация может быть создана
     *
     * @param JewelryBlank $blank Модель заготовки
     * @param Collection|Diamond[] $diamonds Коллекция моделей
     *
     * @return bool
     */
    public function isTypeAccords(JewelryBlank $blank, Collection $diamonds): bool;
}

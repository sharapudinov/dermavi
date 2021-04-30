<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Models\Catalog\Diamond;
use App\Models\Jewelry\JewelryBlank;
use Illuminate\Support\Collection;

/**
 * Интерфейс, описывающий методы определения соответствия бриллианта и заготовки
 * Interface AccordanceInterface
 *
 * @package App\Core\Jewelry\Constructor\Generator
 */
interface AccordanceInterface
{
    /**
     * Возвращает коллекцию моделей бриллиантов, соответствующих заготовке
     *
     * @param JewelryBlank $blank Модель заготовки
     * @param Collection|Diamond[] $diamonds Коллекция моделей бриллиантов
     *
     * @return Collection|Diamond[]
     */
    public function getAccordingDiamonds(JewelryBlank $blank, Collection $diamonds): Collection;
}

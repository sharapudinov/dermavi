<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Models\Catalog\Diamond;
use Illuminate\Support\Collection;

/**
 * Интерфейс, описывающий методы проверки необходимости создания комбинации
 * Interface CombinationAccordanceInterface
 *
 * @package App\Core\Jewelry\Constructor\Generator
 */
interface CombinationAccordanceInterface
{
    /**
     * Возвращает истину, если комбинация прошла проверку на возможность создания
     *
     * @param Collection|Diamond[] $diamonds Коллекция моделей бриллиантов текущей комбинации
     *
     * @return bool
     */
    public function isCombinationAccords(Collection $diamonds): bool;
}

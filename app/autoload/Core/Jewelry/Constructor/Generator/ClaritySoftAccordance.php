<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Models\Catalog\Diamond;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику определения соответствия бриллианта и заготовки по чистоте +- одна группа
 * Class ClaritySoftAccordance
 *
 * @package App\Core\Jewelry\Constructor\Generator
 */
class ClaritySoftAccordance extends ClarityAccordance
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
        if (!$this->isValidDiamondsForClaritiesCount($diamonds)) {
            return false;
        }

        // если камень один, то он не может не подойти
        if ($diamonds->count() === 1) {
            return true;
        }

        return AccordanceHelper::isSoftAccordance(
            $diamonds->pluck('PROPERTY_CLARITY_VALUE')->unique()->toArray(),
            $this->getClaritiesWhiteList($diamonds)
        );
    }
}

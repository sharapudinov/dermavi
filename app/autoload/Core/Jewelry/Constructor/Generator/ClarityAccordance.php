<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Models\Catalog\Diamond;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику определения соответствия бриллианта и заготовки по чистоте
 * Class ClarityAccordance
 *
 * @package App\Core\Jewelry\Constructor\Generator
 */
class ClarityAccordance implements CombinationAccordanceInterface
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
        return $this->isValidDiamondsForClaritiesCount($diamonds)
            && $diamonds->pluck('PROPERTY_CLARITY_VALUE')->unique()->count() === $diamonds->count();
    }

    /**
     * Определяет совпадает ли количество камней в комбинации и количество камней из этой комбинации,
     * которые подходят по чистоте
     *
     * @param Collection|Diamond[] $diamonds Коллекция моделей бриллиантов текущей комбинации
     *
     * @return bool
     */
    protected function isValidDiamondsForClaritiesCount(Collection $diamonds): bool
    {
        return $this->getDiamondsForClarities($diamonds)->count() === $diamonds->count();
    }

    /**
     * Возвращает коллекцию камней, которые подходят по списку значений чистоты
     *
     * @param Collection|Diamond[] $diamonds Коллекция моделей бриллиантов текущей комбинации
     *
     * @return Collection|Diamond[]
     */
    protected function getDiamondsForClarities(Collection $diamonds): Collection
    {
        $claritiesWhiteList = $this->getClaritiesWhiteList($diamonds);

        return $diamonds->filter(static function (Diamond $diamond) use ($claritiesWhiteList) {
            return in_array($diamond->getClarityID(), $claritiesWhiteList, true);
        });
    }

    /**
     * Возвращает массив символьных кодов чистоты, которые доступны в конструкторе
     *
     * @param Collection|Diamond[] $diamonds Коллекция моделей бриллиантов текущей комбинации
     *
     * @return array|string[]
     */
    protected function getClaritiesWhiteList(Collection $diamonds): array
    {
        $claritiesWhitelist = ['if', 'vvs1', 'vvs2', 'vs1', 'vs2', 'si1'];

        if ($diamonds->count() === 1) {
            $claritiesWhitelist[] = 'si2';
        }

        return $claritiesWhitelist;
    }
}

<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Models\Catalog\Diamond;
use App\Models\Jewelry\JewelryBlank;
use Illuminate\Support\Collection;

/**
 * Класс-хелпер для работы с генерацией комбинаций изделий ЮБИ
 * Class AccordanceHelper
 *
 * @package App\Core\Jewelry\Constructor\Generator
 */
class AccordanceHelper
{
    /**
     * Возвращает коллекцию бриллиантов с сохраненными ключами и с проверкой на то,
     * что количество бриллиантов одной группы характеристики не меньше количества слотов в заготовке.
     *
     * @param JewelryBlank $blank Модель заготовки
     * @param Collection|Diamond[][] $groupedDiamonds Коллекция моделей сгруппированных бриллиантов
     *
     * @return Collection|Diamond[]
     */
    public static function getDiamondsForCharacteristic(JewelryBlank $blank, Collection $groupedDiamonds): Collection
    {
        foreach ($groupedDiamonds as $key => $group) {
            /** @noinspection TypeUnsafeComparisonInspection */
            if ($group->count() < $blank->casts->pluck('UF_ITEMS_COUNT')->sum() || $key == '') {
                $groupedDiamonds->forget($key);
            }
        }

        $groupedDiamonds = $groupedDiamonds->collapse();
        return $groupedDiamonds->mapWithKeys(static function (Diamond $diamond) {
            return [$diamond->getID() => $diamond];
        });
    }

    /**
     * Определяет подходят ли значения одного из свойств коллекции бриллиантов для присутствия на одной оправе
     * с учётом правила +- одна группа
     *
     * @param array $values - уникальные значения одного из свойств коллеции бриллиантов
     * @param array $valueList - список допустимых значений этого свойства
     * в порядке по коротому можно определить соседние группы
     *
     * @return bool
     */
    public static function isSoftAccordance(array $values, array $valueList): bool
    {
        // если вариантов значений больше трёх, то они не подойдут под правило +- одна группа
        if (count($values) > 3) {
            return false;
        }

        /**
         * Получаем ключи совпадающих значений из списка доступных для конструктора
         */

        $foundKeys = [];

        foreach ($values as $clarity) {
            foreach ($valueList as $key => $value) {
                if ($clarity === $value) {
                    $foundKeys[] = $key;
                    break;
                }
            }
        }

        /**
         * Если ключи идут подряд, значит это подходит по правилу +- одна группа, если не подряд, то не подходит
         */

        sort($foundKeys);

        foreach ($foundKeys as $foundKey) {
            if (!isset($prevFoundKey)) {
                $prevFoundKey = $foundKey;
            } elseif (($prevFoundKey + 1) !== $foundKey) {
                return false;
            }
        }

        return true;
    }
}

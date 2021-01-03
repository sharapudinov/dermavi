<?php

namespace App\Helpers;

use Generator;

/**
 * Класс-хелпер для работы с массивами
 * Class ArrayHelper
 *
 * @package App\Helpers
 */
class ArrayHelper
{
    /**
     * Реализует алгоритм генерации сочетаний (без повторений)
     * и возвращает класс Generator, который позволяет обойти все возможные (НО УНИКАЛЬНЫЕ) комбинации массива,
     * сгруппированные количеством по $n записей. Возвращается все через yield для оптимизации,
     * иначе в массив записываются по несколько миллионов уникальных комбинаций и оперативка переполняется.
     *
     * @param array|array[] $array Массив, для которого генерируются комбинации
     * @param int $n Количество элементов в группе
     *
     * @return Generator
     */
    public static function getCombinations(array $array, int $n): Generator
    {
        if ($n == 1) {
            foreach($array as $key => $b) {
                yield [$b];
            }

        } else {
            //get one level lower combinations
            $oneLevelLower = self::getCombinations($array,$n - 1);

            //for every one level lower combinations add one element to them that the last element of a combination
            // is preceeded by the element which follows it in base array if there is none, does not add
            foreach ($oneLevelLower as $oll) {
                $lastEl = $oll[$n - 2];
                $found = false;
                foreach ($array as $key => $b) {
                    if ($b == $lastEl) {
                        $found = true;
                        continue;
                        //last element found
                    }
                    if ($found == true) {
                        $arrayLen = count($array);

                        //add to combinations with last element
                        if ($key < $arrayLen) {
                            $tmp = $oll;
                            $newCombination = array_slice($tmp,0);
                            $newCombination[] = $b;
                            yield array_slice($newCombination,0);
                        }
                    }
                }
            }
        }
    }
}

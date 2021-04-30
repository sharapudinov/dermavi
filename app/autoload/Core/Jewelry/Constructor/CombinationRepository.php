<?php

namespace App\Core\Jewelry\Constructor;

use App\Exceptions\ConstructorCombinationException;
use App\Helpers\TTL;
use App\Models\Jewelry\HL\JewelryBlankDiamonds;
use App\Models\Jewelry\JewelryBlank;

/**
 * Класс, описывающий получение комбинаций бриллиантов для заготовок.
 */
class CombinationRepository
{
    /**
     * Возвращает модель комбинации.
     *
     * @param JewelryBlank $blank
     * @param array|int[]  $diamondsId
     * @param int          $combinationId - id комбинации или 0
     *
     * @throws ConstructorCombinationException
     *
     * @return JewelryBlankDiamonds
     */
    public function getCombination(JewelryBlank $blank, array $diamondsId, int $combinationId = 0): JewelryBlankDiamonds
    {
        if ($combinationId) {
            return $this->getCombinationById($combinationId);
        }

        return $this->getCombinationByDiamondsId($blank, $diamondsId);
    }

    /**
     * Возвращает модель комбинации по id.
     *
     * @param int $combinationId
     * @param int $cacheMinutes
     *
     * @throws ConstructorCombinationException
     *
     * @return JewelryBlankDiamonds
     */
    public function getCombinationById(int $combinationId, int $cacheMinutes = 0): JewelryBlankDiamonds
    {
        $result = JewelryBlankDiamonds::cache($cacheMinutes)
            ->filter(['ID' => $combinationId])
            ->getList()
            ->first();

        if (!$result) {
            throw new ConstructorCombinationException(
                sprintf(
                    'Не удалось найти комбинацию по id: %s',
                    $combinationId
                )
            );
        }

        return $result;
    }

    /**
     * Возвращает модель комбинации по id.
     *
     * @param JewelryBlank $blank
     * @param array|int[]  $diamondsId
     *
     * @throws ConstructorCombinationException
     *
     * @return JewelryBlankDiamonds
     */
    public function getCombinationByDiamondsId(JewelryBlank $blank, array $diamondsId): JewelryBlankDiamonds
    {
        $combinations = JewelryBlankDiamonds::cache(TTL::HOUR)->filter([
            'UF_BLANK_ID' => $blank->getId(),
            'UF_DIAMONDS_ID' => $diamondsId,
        ])->getList();

        // функция для приведения значений массива к числовому виду и его сортировка,
        // используется для точного сравнения массивов
        $unificate = static function (array $array) {
            $array = array_map('intval', $array);
            sort($array);

            return $array;
        };

        $result = $combinations->first(
            static function (JewelryBlankDiamonds $combination) use ($diamondsId, $unificate) {
                return $unificate($diamondsId) === $unificate($combination->getDiamondsIds());
            }
        );

        if (!$result) {
            throw new ConstructorCombinationException(
                sprintf(
                    'Не удалось найти комбинацию по id бриллиантов: %s',
                    implode(', ', $diamondsId)
                )
            );
        }

        return $result;
    }
}

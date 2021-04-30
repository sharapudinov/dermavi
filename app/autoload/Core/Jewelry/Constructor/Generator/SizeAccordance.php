<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Helpers\JewelryCastHelper;
use App\Models\Catalog\Diamond;
use App\Models\Jewelry\Dicts\JewelryCast;
use App\Models\Jewelry\JewelryBlank;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику определения соответствия бриллианта и заготовки по размеру
 * Class SizeAccordance.
 */
class SizeAccordance implements AccordanceInterface, CombinationAccordanceTypeInterface
{
    /** @var float Максимальное расхождение в диаметрах (в мм) */
    public const MAX_DIAMETER_DIFF = 0.05;

    /**
     * Возвращает коллекцию моделей бриллиантов, соответствующих заготовке.
     *
     * @param JewelryBlank         $blank    Модель заготовки
     * @param Collection|Diamond[] $diamonds Коллекция моделей бриллиантов
     *
     * @return Collection|Diamond[]
     */
    public function getAccordingDiamonds(JewelryBlank $blank, Collection $diamonds): Collection
    {
        if (!$blank->isRelationByDiameter()) {
            return $diamonds;
        }

        //Если проверка по размеру, то из коллекции удаляются бриллианты без диаметра
        foreach ($diamonds as $key => $diamond) {
            /** @noinspection TypeUnsafeComparisonInspection */
            if (!$diamond->getDiameter() || $diamond->getDiameter() == 0) {
                $diamonds->forget($key);
            }
        }

        $accordingDiamonds = new Collection();
        foreach ($blank->casts as $cast) {
            foreach ($cast->ranges as $range) {
                foreach ($diamonds as $diamond) {
                    $from = (float)$range->getFrom() - self::MAX_DIAMETER_DIFF;
                    $to = (float)$range->getTo() + self::MAX_DIAMETER_DIFF;

                    if ((float) $diamond->getDiameter() >= $from
                        && (float) $diamond->getDiameter() <= $to) {
                        $accordingDiamonds->put($diamond->getId(), $diamond);
                    }
                }
            }
        }

        if ($accordingDiamonds->count() >= $blank->getDiamondsCount()) {
            return $accordingDiamonds;
        }

        return new Collection();
    }

    /**
     * Возвращает истину, если комбинация может быть создана.
     *
     * @param JewelryBlank         $blank    Модель заготовки
     * @param Collection|Diamond[] $diamonds Коллекция моделей
     *
     * @return bool
     */
    public function isTypeAccords(JewelryBlank $blank, Collection $diamonds): bool
    {
        if (!$blank->isRelationByDiameter()) {
            return true;
        }

        /**
         * Сравниваем диаметры бриллиантов при одном касте.
         */
        if ($blank->casts->count() === 1) {
            /** @var JewelryCast $cast */
            $cast = $blank->casts->first();
            /** @noinspection PhpUnusedLocalVariableInspection */
            foreach ($cast->ranges as $range) {
                //Проверям, что все камни имеют одинаковый диаметр (с учетом погрешности)
                /** @var Collection $diameters */
                $diameters = $diamonds->map(static function (Diamond $diamond) {
                    return (float)$diamond->getDiameter();
                });

                if (($diameters->max() - $diameters->min()) > self::MAX_DIAMETER_DIFF) {
                    continue;
                }

                return true;
            }

            return false;
        }

        /**
         * Сравниваем диаметры бриллиантов в трилогии.
         */
        if ($blank->isTrilogy()) {
            // Боковые бриллианты должны отличаться от центрального не более чем на 0.05мм
            $centralDiamondCast = JewelryCastHelper::getCentralCastOfTrilogy($blank);
            $diameters = $diamonds->pluck('PROPERTY_DIAMETER_VALUE');
            $noDiameter = $diameters->first(static function (string $diameter) {
                /** @noinspection TypeUnsafeComparisonInspection */
                return $diameter == '' || $diameter == null || $diameter == 0;
            });

            if ($noDiameter) {
                return false;
            }

            $suitableDiameter = null;
            foreach ($centralDiamondCast->ranges as $range) {
                $suitableDiameter = $diameters->first(static function (string $diameter) use ($range) {
                    return (float) $diameter >= (float) $range->getFrom()
                        && (float) $diameter <= (float) $range->getTo();
                });

                if ($suitableDiameter) {
                    break;
                }
            }

            if (!$suitableDiameter) {
                return false;
            }

            $centralDiamond = JewelryCastHelper::getCentralDiamondOfTrilogy($diamonds, $centralDiamondCast);
            if (!$centralDiamond) {
                return false;
            }

            $notCentralDiamonds = $diamonds->filter(static function (Diamond $diamond) use ($centralDiamond) {
                return $diamond->getId() !== $centralDiamond->getId();
            });
            if ($notCentralDiamonds->isEmpty()) {
                return false;
            }

            /** @var Diamond $firstNotCentralDiamonds */
            $firstNotCentralDiamonds = $notCentralDiamonds->first();

            /** @var Diamond $notCentralDiamond */
            foreach ($notCentralDiamonds as $notCentralDiamond) {
                $diff = abs(
                    (float)$firstNotCentralDiamonds->getDiameter() - (float)$notCentralDiamond->getDiameter()
                );
                if ($diff > self::MAX_DIAMETER_DIFF) {
                    return false;
                }
            }

            $centralDiamondRangeNumber = JewelryCastHelper::getRangeNumber(
                $blank,
                $centralDiamondCast,
                $centralDiamond
            );
            if (!$centralDiamondRangeNumber) {
                return false;
            }

            $centralDiamondRange = JewelryCastHelper::getRangeByNumber($centralDiamondCast, $centralDiamondRangeNumber);
            if (!$centralDiamondRange) {
                return false;
            }

            $notCentralDiameterFrom = round(
                (float)$centralDiamondRange->getFrom() / 1.65,
                2
            );
            $notCentralDiameterTo = round(
                (float)$centralDiamondRange->getTo() / 1.55,
                2
            );

            /** @var Diamond $notCentralDiamond */
            foreach ($notCentralDiamonds as $notCentralDiamond) {
                if (
                    (float)$notCentralDiamond->getDiameter() < ($notCentralDiameterFrom - self::MAX_DIAMETER_DIFF)
                    || (float)$notCentralDiamond->getDiameter() > ($notCentralDiameterTo + self::MAX_DIAMETER_DIFF)
                ) {
                    return false;
                }
            }

            return true;
        }

        /**
         * Сравниваем диаметры бриллиантов для нескольких кастов в случае если это не трилогия.
         *
         * @todo не реализовано, нужно реализовать
         */
        logger('jewelry_constructor_combinations')->error(
            sprintf(
                'Blank #%s. Not implemented check sizes of several casts and not trilogy. Diamonds %s',
                $blank->getId(),
                $diamonds->pluck('ID')
            )
        );

        return false;
    }
}

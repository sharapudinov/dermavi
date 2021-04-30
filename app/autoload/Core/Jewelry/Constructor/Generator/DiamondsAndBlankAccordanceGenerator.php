<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Core\BitrixProperty\Property;
use App\Core\Jewelry\Constructor\BlanksAndDiamonds\BlanksAndDiamondsPrice;
use App\Core\Jewelry\Constructor\BlanksAndDiamonds\BlanksAndDiamondsRepository;
use App\Core\Jewelry\Constructor\Config;
use App\Core\Jewelry\Constructor\Generator\Map\AccordanceMap;
use App\Helpers\ArrayHelper;
use App\Models\Catalog\Diamond;
use App\Models\Jewelry\HL\JewelryBlankDiamonds;
use App\Models\Jewelry\JewelryBlank;
use Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix;
use Bitrix\Main\Application;
use Bitrix\Main\Data\Connection;
use Bitrix\Main\Db\SqlQueryException;
use CIBlockElement;
use Generator;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Класс, описывающий логику генерации комплектов: оправа + бриллиант или оправа + набор бриллиантов
 * Class DiamondsAndBlankAccordanceGenerator
 *
 * @package App\Core\Jewelry\Constructor\Generator
 */
class DiamondsAndBlankAccordanceGenerator
{
    /** @var Collection|JewelryBlank[] $blanks Коллекция заготовок */
    private Collection $blanks;

    /** @var Collection|Diamond[] $diamonds Коллекция бриллиантов */
    private Collection $diamonds;

    /** @var array|int[] $diamondsWhitelist Массив идентификаторов бриллиантов, которые допущены до конструктора */
    private array $diamondsWhitelist = [];

    /** @var bool "Сухой" запуск, если true то не выполняем запросы к базе */
    private bool $dryRun;

    /** @var int Количество созданных комбинаций */
    private int $createdCount = 0;

    /** @var int Количество обновленных комбинаций */
    private int $updatedCount = 0;

    /** @var int Количество удаленных комбинаций */
    private int $deletedCount = 0;

    /** @var array Массив id бриллиантов, которые уже были использованы, в ключах id оправ */
    private array $blanksUsedDiamonds = [];

    /** @var Connection Подключение к БД */
    private Connection $connect;

    /** @var int Метка времени последнего пинга БД */
    private int $pingDbTimestamp = 0;

    /**
     * DiamondsAndBlankAccordanceGenerator constructor.
     *
     * @param bool $dryRun "Сухой" запуск, если true то не выполняем запросы к базе
     * @param array|mixed[] $blankFilter Фильтр для заготовок
     */
    public function __construct(bool $dryRun = false, array $blankFilter = [])
    {
        $this->blanks = BlanksAndDiamondsRepository::getBlanks($blankFilter);
        $this->diamonds = BlanksAndDiamondsRepository::getDiamondsForBlanks();
        $this->dryRun = $dryRun;

        $this->connect = Application::getConnection();
        $this->setPingDbTimestamp();
    }

    /**
     * Записывает в бд варианты соотношений заготовок и бриллиантов
     *
     * @param JewelryBlank $blank Модель заготовки
     * @param Collection|Diamond[] $combination Коллекция бриллиантов, доступных для текущей заготовки
     *
     * @return int
     */
    private function save(JewelryBlank $blank, Collection $combination): int
    {
        try {
            $weightMin = (float)$combination->min(static function (Diamond $diamond) {
                return $diamond['PROPERTY_WEIGHT_VALUE'];
            });

            $weightMax = (float)$combination->max(static function (Diamond $diamond) {
                return $diamond['PROPERTY_WEIGHT_VALUE'];
            });

            $colorMin = (int)$combination->min(static function (Diamond $diamond) {
                return $diamond['PROPERTY_COLOR_SORT_VALUE'];
            });

            $colorMax = (int)$combination->max(static function (Diamond $diamond) {
                return $diamond['PROPERTY_COLOR_SORT_VALUE'];
            });

            $clarityMin = (int)$combination->min(static function (Diamond $diamond) {
                return $diamond['PROPERTY_CLARITY_SORT_VALUE'];
            });

            $clarityMax = (int)$combination->max(static function (Diamond $diamond) {
                return $diamond['PROPERTY_CLARITY_SORT_VALUE'];
            });

            $cutMin = (int)$combination->min(static function (Diamond $diamond) {
                return $diamond['PROPERTY_CUT_SORT_VALUE'];
            });

            $cutMax = (int)$combination->max(static function (Diamond $diamond) {
                return $diamond['PROPERTY_CUT_SORT_VALUE'];
            });

            if ($this->dryRun) {
                return 0;
            }

            $fields = [
                'UF_BLANK_ID' => $blank->getId(),
                'UF_DIAMONDS_ID' => $combination->pluck('ID')->toArray(),
                'UF_PRICE' => (new BlanksAndDiamondsPrice($blank, $combination))->getPrice(),
                'UF_WEIGHT_FROM' => $weightMin,
                'UF_WEIGHT_TO' => $weightMax,
                'UF_COLOR_SORT_FROM' => $colorMin,
                'UF_COLOR_SORT_TO' => $colorMax,
                'UF_CLARITY_SORT_FROM' => $clarityMin,
                'UF_CLARITY_SORT_TO' => $clarityMax,
                'UF_CUT_SORT_FROM' => $cutMin,
                'UF_CUT_SORT_TO' => $cutMax,
                'UF_SELLING_AVAILABLE' => true
            ];

            /** @var JewelryBlankDiamonds $diamond */
            $diamond = JewelryBlankDiamonds::filter([
                'UF_BLANK_ID' => $fields['UF_BLANK_ID'],
                'UF_DIAMONDS_ID' => $fields['UF_DIAMONDS_ID'],
            ])->first();

            if ($diamond) {
                $diamond->update($fields);
                $this->updatedCount++;
            } else {
                $diamond = JewelryBlankDiamonds::create($fields);
                $this->createdCount++;
            }

            return $diamond->getId();
        } catch (Throwable $exception) {
            logger('jewelry_constructor_combinations')
                ->error(
                    'Error when create combination blank #' . $blank->getId()
                    . ' diamonds ' . $combination->pluck('ID')->implode(';')
                    . '. Error: ' . $exception->getMessage()
                );
        }

        return 0;
    }

    /**
     * Разрешает бриллиантам вывод в каталоге конструктора
     *
     * @return void
     */
    private function accessDiamonds(): void
    {
        /**
         * @var \App\Core\BitrixProperty\Entity\Property $canConstructor
         * Объект, описывающий свойство возможности использования бриллианта в конструкторе
         */
        $canConstructor = Property::getListPropertyValue(
            Diamond::iblockID(),
            'CAN_CONSTRUCTOR',
            'Y'
        );

        foreach ($this->diamondsWhitelist as $diamondId) {
            CIBlockElement::SetPropertyValuesEx(
                $diamondId,
                Diamond::iblockID(),
                ['CAN_CONSTRUCTOR' => $canConstructor->getVariantId()]
            );
        }
    }

    /**
     * Проверяет группу бриллиантов на возможность участия в конструкторе
     *
     * @param AccordanceMap $accordanceMap
     * @param JewelryBlank $blank
     * @param int $limit - количество созданных комплектов, после которго мы выходим из метода
     *
     * @return array|int[]
     *
     * @throws SqlQueryException
     */
    private function processBlank(AccordanceMap $accordanceMap, JewelryBlank $blank, int $limit): array
    {
        $blankDiamonds = clone $this->diamonds;

        // убираем из списка бриллиантов те, которые мы уже использовали для данной оправы
        $blankDiamonds->forget($this->blanksUsedDiamonds[$blank->getId()] ?? []);

        $blankDiamondsBeforeCount = $blankDiamonds->count();

        foreach ($accordanceMap->getDefault() as $accordance) {
            $blankDiamonds = $accordance->getAccordingDiamonds($blank, $blankDiamonds);

            if ($blankDiamonds->isEmpty()) {
                logger('jewelry_constructor_combinations')->info(
                    sprintf(
                        'Blank #%s. No diamonds on %s:getAccordingDiamonds',
                        $blank->getId(),
                        get_class($accordance)
                    )
                );
                break;
            }
        }

        $blankDiamondsBeforeLimitCount = $blankDiamonds->count();

        $newIds = [];

        if ($blankDiamonds->isEmpty()) {
            $this->processGroupResultLog(
                $blank,
                $blankDiamondsBeforeCount,
                0,
                0,
                0
            );

            return $newIds;
        }

        $needDiamondsCount = $blank->casts->pluck('UF_ITEMS_COUNT')->sum();

        /**
         * Если подходящих камней много, и для оправы нужен не 1 камень, а больше,
         * то генерация происходит слишком долго, поэтому ограничиваем их количество, если задана такая настройка
         */
        $constructorDiamondsLimit = Config::getDiamondsLimit();
        if ($constructorDiamondsLimit && $needDiamondsCount > 1) {
            $blankDiamonds = $blankDiamonds->slice(0, $constructorDiamondsLimit);
        }

        $blankDiamondsCount = $blankDiamonds->count();

        /**
         * Берём все варианты для камней, если нашли подходящий вариант,
         * то исключаем камни из выборки и перегенерируем варианты
         */
        do {
            /** @var Generator $combinations Уникальные комбинации сочетаний оправы и бриллиантов */
            $blandDiamondIds = $blankDiamonds->keys()->toArray();
            $combinations = ArrayHelper::getCombinations(
                $blandDiamondIds,
                $needDiamondsCount
            );

            $whileContinue = false;

            foreach ($combinations as $key => $combination) {
                $this->pingDbEveryMinute();

                $pluckedDiamonds = new Collection();
                foreach ($combination as $item) {

                    $diamond = $blankDiamonds->get($item);
                    if (!$diamond) {
                        continue 2;
                    }

                    $pluckedDiamonds->push($diamond);
                }

                $canBeCreated = true;
                foreach ($accordanceMap->getCombinationType() as $accordance) {
                    $canBeCreated = $accordance->isTypeAccords($blank, $pluckedDiamonds);

                    if (!$canBeCreated) {
                        /*logger('jewelry_constructor_combinations')
                            ->info(
                                'Blank #' . $blank->getId()
                                . '. Can not create combination ' . $pluckedDiamonds->pluck('ID')
                                . ' on ' . get_class($accordance) . ':isTypeAccords'
                            );*/
                        break;
                    }
                }

                if ($canBeCreated) {
                    foreach ($accordanceMap->getCombination() as $accordance) {
                        $canBeCreated = $accordance->isCombinationAccords($pluckedDiamonds);

                        if (!$canBeCreated) {
                            /*logger('jewelry_constructor_combinations')
                                ->info(
                                    'Blank #' . $blank->getId()
                                    . '. Can not create combination ' . $pluckedDiamonds->pluck('ID')
                                    . ' on ' . get_class($accordance) . ':isCombinationAccords'
                                );*/
                            break;
                        }
                    }
                }

                if ($canBeCreated) {
                    $this->diamondsWhitelist = array_merge($this->diamondsWhitelist, $combination);

                    $diamondId = $this->save($blank, $pluckedDiamonds);
                    if ($diamondId) {
                        $newIds[] = $diamondId;
                    }

                    // продолжаем только если лимит ещё не достигнут
                    $whileContinue = count($newIds) < $limit;

                    /** @noinspection PhpUndefinedMethodInspection */
                    $pluckedDiamondsIds = $pluckedDiamonds->map->getId()->all();
                    $blankDiamonds->forget($pluckedDiamondsIds);

                    // запоминаем какие камни мы уже использовали для данной оправы
                    $this->blanksUsedDiamonds[$blank->getId()] = array_merge(
                        $this->blanksUsedDiamonds[$blank->getId()] ?? [],
                        $pluckedDiamondsIds
                    );
                }
            }
        } while ($whileContinue);

        logger('jewelry_constructor_combinations')->info(
            sprintf(
                'Blank #%s. Count combinations: %d',
                $blank->getId(),
                count($newIds)
            )
        );

        $this->processGroupResultLog(
            $blank,
            $blankDiamondsBeforeCount,
            $blankDiamondsCount,
            $blankDiamondsBeforeLimitCount,
            count($newIds)
        );

        return $newIds;
    }

    /**
     * Выводит на экран результат генерации для данной группы
     *
     * @param JewelryBlank $blank
     * @param int $blankDiamondsBeforeCount - начальное количество после лимитирования и отсекания использованных
     * @param int $blankDiamondsCount - финальное количество после лимитирования
     * @param int $blankDiamondsBeforeLimitCount - общее количество до лимитирования
     * @param int $combinationsCountForBlank
     */
    private function processGroupResultLog(
        JewelryBlank $blank,
        int $blankDiamondsBeforeCount,
        int $blankDiamondsCount,
        int $blankDiamondsBeforeLimitCount,
        int $combinationsCountForBlank
    ): void {
        $this->logResult(
            sprintf(
                '-- blank %-6s, need diamonds %-2s, all diamonds: %-3s, according diamonds: %-3s (before limit: %-3s), combinations: %-3s',
                $blank->getId(),
                $blank->casts->pluck('UF_ITEMS_COUNT')->sum(),
                $blankDiamondsBeforeCount,
                $blankDiamondsCount,
                $blankDiamondsBeforeLimitCount,
                $combinationsCountForBlank
            )
        );
    }

    /**
     * Удаляет более ненужные для конструктора комбинации
     *
     * @param Collection|int[] $ids Массив идентификаторов комбинаций
     *
     * @return int
     *
     * @throws ExceptionFromBitrix
     */
    private function deleteCombinations(Collection $ids): int
    {
        $count = 0;

        if ($this->dryRun) {
            return $count;
        }

        if ($ids) {
            /** @var Collection|JewelryBlankDiamonds[] $rows */
            $rows = JewelryBlankDiamonds::filter(['ID' => $ids->toArray()])->getList();
            $count = $rows->count();
            foreach ($rows as $row) {
                $row->delete();
                $this->deletedCount++;
            }
        }

        return $count;
    }

    /**
     * Определяет все возможные варианты соотношений вставок и заготовок
     *
     * @return void
     *
     * @throws ExceptionFromBitrix
     * @throws SqlQueryException
     */
    public function execute(): void
    {
        /** @var array|int[] $newIds Массив идентификаторов созданных комбинаций */
        $newIds = [];

        /** @var array|int[] $counter - Количество созданных комбинаций по каждой из оправ */
        $counter = [];

        /**
         * @var int $limit - количество комбинаций по каждой оправе, после набора которого мы перестаём
         * переходить к следующей карте подбора.
         */
        $limit = Config::getBlankDiamondsLimit();

        foreach ($this->getAccordancesMaps() as $accordanceName => $accordanceMap) {
            $this->logResult(sprintf('Processing accordance map %s:', $accordanceName));

            foreach ($this->blanks as $blank) {
                $blankId = $blank->getId();

                // если для оправы уже набрано необходимое количество комбинаций, то пропускаем её
                if ($limit && ($counter[$blankId] ?? 0) >= $limit) {
                    $this->logResult(sprintf(
                        '-- blank %-6s skip, because combinations count (%s) reached limit (%s)',
                        $blankId,
                        ($counter[$blankId] ?? 0),
                        $limit
                    ));
                    continue;
                }

                $blankNewIds = $this->processBlank($accordanceMap, $blank, $limit);

                $counter[$blankId] = ($counter[$blankId] ?? 0) + count($blankNewIds);

                $newIds = array_merge($newIds, $blankNewIds);
            }
        }

        /** @var Collection|int[] $existIds Коллекция идентификаторов созданных ранее комбинаций */
        $existIds = JewelryBlankDiamonds::getList()->pluck('ID');

        $this->deleteCombinations($existIds->diff($newIds));

        logger('jewelry_constructor_combinations')->info(
            sprintf(
                'Complete updated: %d created: %d deleted: %d',
                $this->updatedCount,
                $this->createdCount,
                $this->deletedCount
            )
        );

        $this->accessDiamonds();
    }

    /**
     * Возвращает коллецию карт подбора (список правил для определения соответствий бриллиантов и заготовок),
     * сначала более строгие карты подробоа, потом менее строгие
     *
     * @return Collection|AccordanceMap[]
     */
    private function getAccordancesMaps(): Collection
    {
        $result = new Collection;

        $fluorescenceAccordance = new FluorescenceAccordance;
        $colorAccordance = new ColorAccordance;
        $colorSoftAccordance = new ColorSoftAccordance;
        $sizeAccordance = new SizeAccordance;
        $shapeAccordance = new ShapeAccordance;
        $weightAccordance = new WeightAccordance;
        $clarityAccordance = new ClarityAccordance;
        $claritySoftAccordance = new ClaritySoftAccordance;

        /**
         * Карта подбора со строгим соответствием чистоты и цвета
         */

        $map = (new AccordanceMap)
            ->setDefault([$shapeAccordance, $fluorescenceAccordance, $colorAccordance, $sizeAccordance])
            ->setCombinationType([$sizeAccordance, $weightAccordance])
            ->setCombination([$fluorescenceAccordance, $colorAccordance, $clarityAccordance]);

        $result->put(
            '1 - strict clarity, strict color',
            $map
        );

        /**
         * Карта подбора с нестрогим подбором чистоты: +- одна группа
         */

        $result->put(
            '2 - soft clarity, strict color',
            (clone $map)->setCombination([$fluorescenceAccordance, $colorAccordance, $claritySoftAccordance])
        );

        /**
         * Карта подбора с нестрогим подбором чистоты и цвета: +- одна группа
         */

        $result->put(
            '3 - soft clarity, soft color',
            (clone $map)->setCombination([$fluorescenceAccordance, $colorSoftAccordance, $claritySoftAccordance])
        );

        return $result;
    }

    /**
     * Выводит на экран и сохраняет в лог строку
     *
     * @param string $message
     */
    private function logResult(string $message): void
    {
        logger('jewelry_constructor_combinations_result')->info($message);
        dump($message);
    }

    /**
     * Оживляем соединение с БД раз в минуту
     *
     * @throws SqlQueryException
     */
    private function pingDbEveryMinute(): void
    {
        // если с прошлого оживления прошло меньше минуты, то выходим
        if ((time() - $this->pingDbTimestamp) < 60) {
            return;
        }

        $this->connect->query('SELECT 1'); // ping

        $this->setPingDbTimestamp();
    }

    /**
     * Сохраняет текущую метку времени
     */
    private function setPingDbTimestamp(): void
    {
        $this->pingDbTimestamp = time();
    }
}

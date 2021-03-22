<?php

namespace App\Core\Traits\Tracing;

use App\Models\Catalog\Diamond;
use App\Models\Tracing\HL\AudioPart;
use Illuminate\Support\Collection;

/**
 * Трейт для работы с трейсингом
 * Trait TraicingPartsTrait
 * @package App\Core\Traits\Tracing
 */
trait TraicingPartsTrait
{
    abstract public function getElementList(): array;

    protected static function getAllElementByScenario(int $scenarioNumber, int $ttlMinutes = 0): Collection
    {
        return static::query()
            ->filter([
                'UF_ACTIVE' => 1,
                'UF_SCENARIO_NUMBER' => $scenarioNumber,
            ])
            ->sort('UF_INDEX', 'ASC')
            ->cache($ttlMinutes)
            ->getList();
    }

    public static function getIncludedElements(): array
    {
        return static::getList()
            ->filter(function ($item) {
                return !empty($item->getCauseParamName());
            })
            ->groupBy('UF_INDEX')
            ->filter(function ($item) {
                return ($item->count() == 1);
            })
            ->keys()
            ->toArray();
    }

    /**
     * Список актуальных элементов трейсинга
     *
     * @param integer $scenarioNumber
     * @param array $options
     * @param integer $ttlMinutes
     * @return Collection
     */
    public static function getElementsByScenario(int $scenarioNumber, array $options, int $ttlMinutes = 0): Collection
    {
        $list = [];
        $elements = static::getAllElementByScenario($scenarioNumber, $ttlMinutes);
        /** @var AudioPart $element */
        foreach ($elements as $element) {
            if (!$element->check($options)) {
                continue;
            }

            $list[$element->getIndex()] = $element;
        }

        return new Collection(array_values($list));
    }

    /**
     * Массив данных для сборки трейсинга на фронте
     *
     * @param Collection $elements
     * @param Diamond $diamond Модель брилианта
     *
     * @return array
     */
    public static function getListTElements(Collection $elements, Diamond $diamond): array
    {
        $list = [];
        foreach ($elements as $element) {
//            if ($diamond->auctionLot && !$diamond->auctionLot->showClarity() && ($element['UF_CAUSE_PARAM_NAME'] == 'clarity') || $element['UF_ADD_C_PARAM_NAME'] == 'clarity') {
//                continue;
//            }

            $data = $element->getElementList();

            if (empty($data)) {
                continue;
            }

            $list[] = $data;
        }

        return $list;
    }

    /**
     * Массив данных для сборки трейсинга на фронте с выборкой по сценарию и опциям
     *
     * @param integer $scenarioNumber
     * @param array $options
     * @param integer $ttlMinutes
     * @return array
     */
    public static function getListByScenario(int $scenarioNumber, array $options, int $ttlMinutes = 0): array
    {
        $elements = static::getElementsByScenario($scenarioNumber, $options, $ttlMinutes);
        return static::getListTElements($elements);
    }

    private function check(array $options): bool
    {
        $paramName = $this->getCauseParamName();
        $addParamName = $this->getAddCauseParamName();

        if (empty($paramName) && empty($addParamName)) {
            return true;
        }

        //dump("paramName: " . $this->getIndex() . " - " . $paramName . " - " . $options[$paramName] . " - " . $this->getCauseParamValue());
        if (!empty($paramName)) {
            $paramValue = $this->getCauseParamValue();

            if ($paramValue == 'N' && $options[$paramName] != 'N' && $options[$paramName] && !empty($options[$paramName])) {
                return false;
            }

            if ($paramValue == 'Y' && !$options[$paramName] ||
                $paramValue == 'Y' && $options[$paramName] == 'N' ||
                $paramValue == 'Y' && empty($options[$paramName])) {
                return false;
            }

            if ($paramValue != 'Y' && $paramValue != 'N' && $paramValue != $options[$paramName]) {
                return false;
            }
        }

        //dump("addParamName: " . $this->getIndex() . " - " . $addParamName . " - " . $options[$addParamName] . " - " . $this->getAddCauseParamValue());
        if (!empty($addParamName)) {
            $addParamValue = $this->getAddCauseParamValue();

            if ($addParamValue == 'N' && $options[$addParamValue] != 'N' &&
                $options[$addParamName] && !empty($options[$addParamName])) {
                return false;
            }

            if ($addParamValue == 'Y' && !$options[$addParamName] ||
                $addParamValue == 'Y' && $options[$addParamName] == 'N' ||
                $addParamValue == 'Y' && empty($options[$addParamName])) {
                return false;
            }

            if ($addParamValue != 'Y' && $addParamValue != 'N' && $addParamValue != $options[$addParamName]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Имя параметра с условием
     *
     * @return string
     */
    public function getCauseParamName(): string
    {
        return (string) $this['UF_CAUSE_PARAM_NAME'];
    }

    /**
     * Значение параметра с условием
     *
     * @return string
     */
    public function getCauseParamValue(): string
    {
        return (string) $this['UF_CAUSE_PARAM_VALUE'];
    }

    /**
     * Имя доп. параметра с условием
     *
     * @return string
     */
    public function getAddCauseParamName(): string
    {
        return (string) $this['UF_ADD_C_PARAM_NAME'];
    }

    /**
     * Значение доп. параметра с условием
     *
     * @return string
     */
    public function getAddCauseParamValue(): string
    {
        return (string) $this['UF_ADD_C_PARAM_VALUE'];
    }

    /**
     * Активность элемента
     *
     * @return boolean
     */
    public function isActive(): bool
    {
        return (bool) $this['UF_ACTIVE'];
    }

    /**
     * Номер сценария
     *
     * @return integer
     */
    public function getScenarioNumber(): int
    {
        return (int) $this['UF_SCENARIO_NUMBER'];
    }

    /**
     * Индекс сортировки
     *
     * @return integer
     */
    public function getIndex(): int
    {
        return (int) $this['UF_INDEX'];
    }
}

<?php

namespace App\Core\Jewelry\Constructor\Generator\Map;

use App\Core\Jewelry\Constructor\Generator\AccordanceInterface;
use App\Core\Jewelry\Constructor\Generator\CombinationAccordanceInterface;
use App\Core\Jewelry\Constructor\Generator\CombinationAccordanceTypeInterface;
use Illuminate\Support\Collection;
use RuntimeException;

/**
 * Класс для передачи карты подбора.
 * Содержит списки классов для определения соответствуют ли камни оправе и вообще конструктору.
 *
 * Class AccordanceMap
 * @package App\Core\Jewelry\Constructor\Generator\Map
 */
class AccordanceMap
{
    /** @var Collection|AccordanceInterface[] */
    private Collection $default;

    /** @var Collection|CombinationAccordanceTypeInterface[] */
    private Collection $combinationType;

    /** @var Collection|CombinationAccordanceInterface[] */
    private Collection $combination;

    /**
     * @param AccordanceInterface[] $accordances
     * @return self
     */
    public function setDefault(array $accordances): self
    {
        array_map(static function ($accordance) {
            if (!$accordance instanceof AccordanceInterface) {
                throw new RuntimeException('Incorrect class');
            }
        }, $accordances);

        $this->default = new Collection($accordances);

        return $this;
    }

    /**
     * @return Collection|AccordanceInterface[]
     */
    public function getDefault(): Collection
    {
        return $this->default;
    }

    /**
     * @param array|CombinationAccordanceTypeInterface[] $accordances
     * @return self
     */
    public function setCombinationType(array $accordances): self
    {
        array_map(static function ($accordance) {
            if (!$accordance instanceof CombinationAccordanceTypeInterface) {
                throw new RuntimeException('Incorrect class');
            }
        }, $accordances);

        $this->combinationType = new Collection($accordances);

        return $this;
    }

    /**
     * @return Collection|CombinationAccordanceTypeInterface[]
     */
    public function getCombinationType(): Collection
    {
        return $this->combinationType;
    }

    /**
     * @param array|CombinationAccordanceInterface[] $accordances
     * @return self
     */
    public function setCombination(array $accordances): self
    {
        array_map(static function ($accordance) {
            if (!$accordance instanceof CombinationAccordanceInterface) {
                throw new RuntimeException('Incorrect class');
            }
        }, $accordances);

        $this->combination = new Collection($accordances);

        return $this;
    }

    /**
     * @return Collection|CombinationAccordanceInterface[]
     */
    public function getCombination(): Collection
    {
        return $this->combination;
    }
}

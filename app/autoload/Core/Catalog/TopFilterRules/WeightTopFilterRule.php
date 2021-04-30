<?php

namespace App\Core\Catalog\TopFilterRules;

use App\Models\Catalog\Catalog;
use App\Models\Catalog\CatalogSection;
use Arrilot\BitrixModels\Queries\ElementQuery;
use Illuminate\Support\Collection;

/**
 * Отбирает бриллианты с лучшими показателями цвета.
 *
 * Class ColorTopFilterRule
 * @package App\Core\Catalog\Top
 */
class WeightTopFilterRule extends AbstractTopFilterRule
{
    /**
     * @var array Наименование правила
     */
    protected $name = [
        'ru' => 'Самый большой',
        'en' => 'The biggest one',
        'cn' => '最大的'
    ];

    /**
     * Выполняет запрос.
     *
     * @param ElementQuery $query
     * @return Collection|Catalog[]
     */
    protected function select(ElementQuery $query): Collection
    {
        /** @var Collection|Catalog[] $diamonds - Коллекция */
        $diamonds = $query->fromSectionWithCode(CatalogSection::FOR_PHYSIC_PERSONS_SECTION_CODE)
            ->filter($this->generateFilter())
            ->sort('PROPERTY_WEIGHT', 'DESC')
            ->forPage(1, $this->getCount())
            ->getList();

        return static::sortCollection($diamonds, 'PROPERTY_WEIGHT_VALUE');
    }
}

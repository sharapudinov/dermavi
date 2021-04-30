<?php

namespace App\Core\Catalog\TopFilterRules;

use App\Models\Catalog\Diamond;
use App\Models\Catalog\CatalogSection;
use App\Models\Catalog\Catalog;
use Arrilot\BitrixModels\Queries\ElementQuery;
use Illuminate\Support\Collection;

/**
 * Отбирает бриллианты с наилучшей прозрачностью.
 *
 * Class ClarityTopFilterRule
 * @package App\Core\Catalog\Top
 */
class ClarityTopFilterRule extends AbstractTopFilterRule
{
    /**
     * @var array Наименование правила
     */
    protected $name = [
        'ru' => 'Самый чистый',
        'en' => 'The cleanest one',
        'cn' => '最纯净'
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
            ->filter($this->generateFilter(['!PROPERTY_CLARITY_SORT' => false]))
            ->sort('PROPERTY_CLARITY_SORT', 'ASC')
            ->forPage(1, $this->getCount())
            ->getList();

        return static::sortCollection($diamonds, 'PROPERTY_CLARITY_SORT_VALUE');
    }
}

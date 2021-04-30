<?php

namespace App\Core\Catalog\TopFilterRules;

use App\Models\Catalog\Diamond;
use App\Models\Catalog\CatalogSection;
use Arrilot\BitrixModels\Queries\ElementQuery;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Отбирает бриллианты с наименьшей ценой за карат.
 *
 * Class PriceTopFilterRule
 * @package App\Core\Catalog\Top
 */
class PriceTopFilterRule extends AbstractTopFilterRule
{
    /**
     * @var array Наименование правила
     */
    protected $name = [
        'ru' => 'Лучшая цена',
        'en' => 'Best price',
        'cn' => '最好的价格'
    ];

    /**
     * Выполняет запрос.
     *
     * @param ElementQuery $query
     * @return Collection|Diamond[]
     */
    protected function select(ElementQuery $query): Collection
    {
        /** @var Collection|Diamond[] $diamonds - Коллекция */
        $diamonds = $query->fromSectionWithCode(CatalogSection::FOR_PHYSIC_PERSONS_SECTION_CODE)
            ->filter($this->generateFilter())
            ->sort('PROPERTY_PRICE', 'ASC')
            ->forPage(1, $this->getCount())
            ->getList();

        return static::sortCollection($diamonds, 'PROPERTY_PRICE_VALUE');
    }
}

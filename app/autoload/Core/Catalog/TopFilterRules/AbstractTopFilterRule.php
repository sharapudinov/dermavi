<?php

namespace App\Core\Catalog\TopFilterRules;

use App\Core\Catalog\FilterFields\DiamondsFilterFields;
use App\Core\Catalog\FilterFields\ProductFilterFields;
use App\Helpers\LanguageHelper;
use App\Models\Catalog\Diamond;
use App\Models\Catalog\Catalog;
use Arrilot\BitrixModels\Queries\ElementQuery;
use Illuminate\Support\Collection;

/**
 * Базовый класс для правил отбора товаров в ТОП рекомендуемых.
 *
 * Class AbstractTopFilterRule
 * @package App\Core\Catalog\Top
 */
abstract class AbstractTopFilterRule
{
    /** @var array Имя правила на разных языках. Выводится как ярлык на миникарточках. */
    protected $name = [];

    /** @var int Количество отбираемых бриллиантов */
    protected $count = 5;

    /** @var Collection|Diamond[] Отобранные бриилианты */
    protected $diamonds;

    /** @var array|string[] $generalFilterProperties - Массив свойств для фильтрации, общих для всех правил */
    protected $generalFilterProperties = [
        'ACTIVE',
        'PROPERTY_PRICE'
    ];

    /**
     * AbstractTopFilterRule constructor.
     */
    public function __construct()
    {
        $this->diamonds = collect();
    }

    /**
     * Реализует выборку бриллиантов.
     *
     * @param ElementQuery $query объект запроса
     * @return Collection|Diamond[]
     */
    abstract protected function select(ElementQuery $query): Collection;

    /**
     * Сортирует коллекцию (Если есть более одного бриллианта с нужным свойством, то происходит перемешивание)
     *
     * @param Collection|Diamond[] $diamonds - Коллекция бриллиантов
     * @param string $property - Свойство, по которому производится сортировка
     * @return Collection
     */
    protected static function sortCollection(Collection $diamonds, string $property): Collection
    {
        foreach ($diamonds->groupBy($property) as &$group) {
            /** @var Collection|Diamond[] $group - Группа бриллиантов по весу */
            $group->shuffle();
        }

        return $diamonds;
    }

    /**
     * Генерирует массив, описывающий фильтр для запроса
     *
     * @param array $filterArray - Дополнительный фильтр для мерджа
     * @return array
     */
    protected function generateFilter(array $filterArray = []): array
    {
        return array_merge(ProductFilterFields::getFilterForCatalog(), [
            'LOGIC' => 'AND',
            ['!PROPERTY_PRICE' => false],
            ['!PROPERTY_PRICE' => '0']
        ], $filterArray);
    }

    /**
     * Применяет правило и отбирает бриллианты в БД.
     *
     * @param int $cacheTime время кэширования результатов
     */
    public function apply(int $cacheTime = 0): void
    {
        $cacheKey = 'top_' . get_class($this);
        $this->diamonds = cache($cacheKey, $cacheTime, function () {
            return $this->select(Catalog::active());
        });
    }

    /**
     * Возвращает наименование на текущем языке.
     *
     * @return string
     */
    public function getName(string $language = null): string
    {
        $language = $language ?? LanguageHelper::getLanguageVersion();
        return (string) $this->name[$language];
    }

    /**
     * Возвращает количество отбираемых бриллиантов.
     *
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Устанавливает отбираемое количество.
     *
     * @param int $count
     * @return static
     */
    public function setCount(int $count): self
    {
        $this->count = $count;
        return $this;
    }

    /**
     * Возвращает выбранные бриллианты.
     *
     * @return Diamond[]|Collection
     */
    public function getDiamonds(): Collection
    {
        return $this->diamonds;
    }
}

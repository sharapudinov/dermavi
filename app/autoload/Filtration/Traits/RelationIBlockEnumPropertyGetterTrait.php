<?php

namespace App\Filtration\Traits;

use App\Filtration\Exception\ArgumentEmptyException;
use CIBlockPropertyEnum;

/**
 * Trait RelationIBlockEnumPropertyGetterTrait
 *
 * @package App\Filtration\Traits
 */
trait RelationIBlockEnumPropertyGetterTrait
{
    /** @var array */
    private $enumPropertySort = [];
    
    /** @var array */
    private $enumPropertyBaseFilter = [];
    
    /** @var callable */
    private $enumPropertyItemTransformer;

    /**
     * @param array $enumIdList
     * @param int $iblockId
     * @param int $queryChunkSize Ограничение элементов на один запрос
     * @return array
     * @throws ArgumentEmptyException
     */
    public function getEnumPropertyValues(array $enumIdList, int $iblockId = 0, int $queryChunkSize = 0): array
    {
        if (!$enumIdList) {
            throw new ArgumentEmptyException('Argument enumIdList is empty');
        }

        $result = [];
        /** @todo Внимание! Разбиение запроса на куски делает сортировку на уровне БД бессмысленной */
        $chunks = $queryChunkSize > 0 ? array_chunk($enumIdList, $queryChunkSize) : [$enumIdList];
        foreach ($chunks as $chunk) {
            $filter = $this->getEnumPropertyBaseFilter();
            $filter['ID'] = $chunk;
            if ($iblockId > 0) {
                $filter['IBLOCK_ID'] = $iblockId;
            }

            /** @todo Кеширование */
            $iterator = CIBlockPropertyEnum::GetList(
                $this->getEnumPropertySort(),
                $filter
            );
            while ($item = $iterator->Fetch()) {
                $item = $this->transformEnumPropertyItem($item);
                if ($item) {
                    $result[$item['ID']] = $item;
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getEnumPropertySort(): ?array
    {
        return $this->enumPropertySort;
    }

    /**
     * @param array $enumPropertySort
     * @return static
     */
    public function setEnumPropertySort(array $enumPropertySort)
    {
        $this->enumPropertySort = $enumPropertySort;

        return $this;
    }

    /**
     * @return array
     */
    public function getEnumPropertyBaseFilter(): ?array
    {
        return $this->enumPropertyBaseFilter;
    }

    /**
     * @param array $enumPropertyBaseFilter
     * @return static
     */
    public function setEnumPropertyBaseFilter(array $enumPropertyBaseFilter)
    {
        $this->enumPropertyBaseFilter = $enumPropertyBaseFilter;

        return $this;
    }

    /**
     * @return callable|null
     */
    public function getEnumPropertyItemTransformer(): ?callable
    {
        return $this->enumPropertyItemTransformer;
    }

    /**
     * Задает колбек для трансформации значения, полученного из выборки.
     * N.B. Если нужна возможность сохранения объекта в кеше, то сюда лучше не передавать анонимные функции
     *
     * @param callable $enumPropertyItemTransformer
     * @return static
     */
    public function setEnumPropertyItemTransformer(callable $enumPropertyItemTransformer)
    {
        $this->enumPropertyItemTransformer = $enumPropertyItemTransformer;

        return $this;
    }

    /**
     * @param array $item
     * @return array
     */
    protected function transformEnumPropertyItem(array $item): array
    {
        $transformer = $this->getEnumPropertyItemTransformer();

        return $transformer ? (array)$transformer($item) : $item;
    }
}

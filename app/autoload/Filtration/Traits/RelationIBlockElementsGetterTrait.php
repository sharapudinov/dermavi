<?php

namespace App\Filtration\Traits;

use App\Filtration\Exception\ArgumentEmptyException;
use CIBlockElement;

/**
 * Trait RelationIBlockElementsGetterTrait
 *
 * @package App\Filtration\Traits
 */
trait RelationIBlockElementsGetterTrait
{
    /** @var array */
    private $elementsListSort = [];

    /** @var array */
    private $elementsListBaseFilter = [
        'ACTIVE' => 'Y',
        'ACTIVE_DATE' => 'Y',
    ];

    /** @var array */
    private $elementsListSelect = [
        'ID', 'IBLOCK_ID', 'NAME', 'CODE', 'XML_ID', 'SORT',
    ];

    /** @var callable */
    private $elementsListItemTransformer;

    /**
     * @param array $elementsIdList
     * @param int $iblockId
     * @param int $queryChunkSize Ограничение элементов на один запрос
     * @return array
     * @throws ArgumentEmptyException
     */
    public function getElementsList(array $elementsIdList, int $iblockId = 0, int $queryChunkSize = 0): array
    {
        if (!$elementsIdList) {
            throw new ArgumentEmptyException('Argument elementsIdList is empty');
        }

        $result = [];
        /** @todo Внимание! Разбиение запроса на куски делает сортировку на уровне БД бессмысленной */
        $chunks = $queryChunkSize > 0 ? array_chunk($elementsIdList, $queryChunkSize) : [$elementsIdList];
        foreach ($chunks as $chunk) {
            $filter = $this->getElementsListBaseFilter();
            $filter['ID'] = $chunk;
            if ($iblockId > 0) {
                $filter['IBLOCK_ID'] = $iblockId;
            }

            /** @todo Кеширование */
            $iterator = CIBlockElement::GetList(
                $this->getElementsListSort(),
                $filter,
                false,
                false,
                $this->getElementsListSelect()
            );
            while ($item = $iterator->Fetch()) {
                $item = $this->transformElementsListItem($item);
                if ($item) {
                    $result[$item['ID']] = $item;
                }
            }
        }

        return $result;
    }

    /**
     * @param array $elementsListSelect
     * @return static
     */
    public function setElementsListSelect(array $elementsListSelect)
    {
        $this->elementsListSelect = $elementsListSelect;

        return $this;
    }

    /**
     * @return array
     */
    public function getElementsListSort(): array
    {
        return $this->elementsListSelect;
    }

    /**
     * @return array
     */
    public function getElementsListBaseFilter(): array
    {
        return $this->elementsListBaseFilter;
    }

    /**
     * @param array $elementsListBaseFilter
     * @return static
     */
    public function setElementsListBaseFilter(array $elementsListBaseFilter)
    {
        $this->elementsListBaseFilter = $elementsListBaseFilter;

        return $this;
    }

    /**
     * @return array
     */
    protected function getElementsListSelect(): array
    {
        return $this->elementsListSort;
    }

    /**
     * @param array $elementsListSort
     * @return static
     */
    public function setElementsListSort(array $elementsListSort)
    {
        $this->elementsListSort = $elementsListSort;

        return $this;
    }

    /**
     * @return callable|null
     */
    protected function getElementsListItemTransformer(): ?callable
    {
        return $this->elementsListItemTransformer;
    }

    /**
     * Задает колбек для трансформации значения, полученного из выборки.
     * N.B. Если нужна возможность сохранения объекта в кеше, то сюда лучше не передавать анонимные функции
     *
     * @param callable $elementsListItemTransformer
     * @return static
     */
    public function setElementsListItemTransformer(callable $elementsListItemTransformer)
    {
        $this->elementsListItemTransformer = $elementsListItemTransformer;

        return $this;
    }

    /**
     * @param array $item
     * @return array
     */
    protected function transformElementsListItem(array $item): array
    {
        $transformer = $this->getElementsListItemTransformer();

        return $transformer ? (array)$transformer($item) : $item;
    }
}
